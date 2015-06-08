<?
namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\db\Query;
use app\models\Order;
use app\models\Priority;
use app\models\Status;
use app\models\Category;
use app\models\User;
use yii\filters\auth\HttpBasicAuth;

class ApiController extends ActiveController
{
   	public $modelClass = 'app\models\Order';
   	private $error;

    public function init()
	{
	    parent::init();
	    \Yii::$app->user->enableSession = false;
	}

	public function actions()
    {
        return [];
    }

    protected function verbs()
    {
    	return [
    		'auth'   => ['POST'],
    		'list'   => ['GET'],
    		'update' => ['PUT', 'PATCH'],
    		'delete' => ['DELETE']
    	];
    }

    public function behaviors()
	{
	    $behaviors = parent::behaviors();
	    $behaviors['authenticator'] = [
	        'class' => HttpBasicAuth::className(),
	        'except' => ['auth']
	    ];
	    return $behaviors;
	}

	// POST
	public function actionAuth($login, $password)
	{
		$result = ["result" => false];

		if (empty($login)) {
			$result["error"] = "Пустой логин";
		} else if (empty($password)) {
			$result["error"] = "Пустой пароль";
		} else {
			$user = User::find()->where(['login' => $login])->one();

			if (empty($user)) {
				$result["error"] = "Пользователь $login не найден";
			} else if (!$user->validatePassword($password)) {
				$result["error"] = "Неверный пароль для логина $login";
			} else {
				$result["result"] = true;
				$result["token"] = $user->access_token;
				$result["group"] = $user->getGroup()->code;
			}
		}

		return $result;
	}

	// GET
    public function actionList()
	{
		$result = $this->getPermissionResult(['admin', 'manager']);

		if (!$result) {
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$newStatus = Status::find()->where(['code' => 'new'])->one();

		if (empty($newStatus)) {
			$this->error = 'Ошибка в структуре БД. Не найден статус заявки с кодом new';
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$user = Yii::$app->user->identity;
		$userGroup = $user->getGroup();

		$query = new Query;
		$query->select([
					Order::tableName().'.*',
					User::tableName().'.login as user_sender_login',
					User::tableName().'.name as user_sender_name',
					User::tableName().'.last_name as user_sender_lastname',
					User::tableName().'.second_name as user_sender_secondname',
					Priority::tableName().'.name as priority_name',
					Priority::tableName().'.code as priority_code',
					Status::tableName().'.code as status_code',
					Status::tableName().'.name as status_name',
					Category::tableName().'.name as category_name',
				])  
				->from(Order::tableName())
				->leftJoin(
						Priority::tableName(),
						Priority::tableName().'.id = '.Order::tableName().'.priority_id'
				)
				->leftJoin(
						User::tableName(),
						User::tableName().'.id = '.Order::tableName().'.user_sender'
				)
				->leftJoin(
						Status::tableName(),
						Status::tableName().'.id = '.Order::tableName().'.status_id'
				)
				->leftJoin(
						Category::tableName(),
						Category::tableName().'.id = '.Order::tableName().'.category_id'
				);

		if ($userGroup->code == 'manager') {
			$query = $query
						->where(['status_id' => $newStatus->id, 'user_answer' => null])
						->orWhere(['user_answer' => $user->id]);
		}

		$command 		= $query->createCommand();
		$data 			= $command->queryAll();

		return [
			'result' => true,
			'data'   => $data
		];
	}

	// PUT | PATCH
	public function actionUpdate()
	{
		$requestBody = Yii::$app->request->getRawBody();
		$data = Json::decode($requestBody);

		if (empty($data)) {
			$this->error = "Неверный формат параметров";
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$id = intval($data['id']);
		$fields = $data["fields"];

		$result = $this->getPermissionResult(['admin', 'manager']);

		if (!$result) {
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$order = Order::find()->where(['id' => $id])->one();

		if (empty($order)) {
			$this->error = "Заявки с id = $id не существует";
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$user = Yii::$app->user->identity;

		if ($order->user_answer > 0 && $order->user_answer != $user->id) {
			$manager = User::find()->where(['id' => $order->user_answer])->one();
			$this->error = "Заявка с id = $id уже занята другим сотрудником c логином ".$manager->login.
							"Попробуйте удалить эту заявку в своей локальной БД или повтоите попытку синхронизации заявок";
			return [
				"result" => false,
				"error" => $this->error
			];
		}
		
		if (empty($order->user_answer)) {
			$order->user_answer = $user->id;
		}

		if (!empty($fields['complexity'])) {
			$order->complexity = intval($fields['complexity']);
		}

		if (!empty($fields['time_hours'])) {
			$order->time_hours = intval($fields['time_hours']);
		}

		if (!empty($fields['status_code'])) {
			$status = Status::find()->where(['code' => $fields['status_code']])->one();

			if (empty($status)) {
				$this->error = "У заявки не существует статус с кодом ".$fields['status_code'];
				return [
					"result" => false,
					"error" => $this->error
				];
			}

			$order->status_id = $status->id;
		}

		if (!$order->save()) {
			$errorArray = array_keys($order->getFirstErrors());
			$this->error = $order->getFirstError(end($errorArray));
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		return ["result" => true];
	}

	// DELETE
	public function actionDelete($id)
	{
		$result = $this->getPermissionResult(['admin']);

		if (!$result) {
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		$order = Order::find()->where(['id' => $id])->one();

		if (empty($order)) {
			$this->error = "Заявки с id = $id не существует";
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		if ($order->delete() === false) {
			$this->error = "Ошибка удаления заявки с id = $id";
			return [
				"result" => false,
				"error" => $this->error
			];
		}

		return ["result" => true];
	}

	private function getPermissionResult($groupArray)
	{
		$user = Yii::$app->user->identity;
		$login = $user->login;

		$userGroup = $user->getGroup();

		if (empty($userGroup)) {
			$this->error = "У пользователя $login нет прав на заявки. Группа пользователей не определена.";
			return false;
		}

		if (!in_array($userGroup->code, $groupArray)) {
			$this->error = "У пользователя $login нет права на совершения данного действия";
			return false;
		}

		return true;
	}
}
?>