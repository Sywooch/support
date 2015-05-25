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

    /*public function behaviors()
	{
	    return [            
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'list'   => ['post'],
	                'update' => ['post'],
	                'delete' => ['post']
	            ],
	        ],
	    ];
	}*/

	// GET
    public function actionList($login, $password)
	{
		$result = $this->getPermissionResult($login, $password, ['admin', 'manager']);

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

		$query = new Query;
		$query	->select([
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
				)
				->where(['status_id' => $newStatus->id, 'user_answer' => null])
				->orWhere(['user_answer' => $user->id]);
				
		$command 		= $query->createCommand();
		$data 			= $command->queryAll();

		/*
		$result['data'] = Order::find()
			->joinWith('priority')
			->select([
				//Order::tableName().'.id as order_id',
				Order::tableName().'.name AS order_name',
				Priority::tableName().'.name AS priority_name',
				//Priority::tableName().'.code',
			])
			->where(['status_id' => $newStatus->id, 'user_answer' => null])
			->orWhere(['user_answer' => $user->id])
			->all();
		*/

		return [
			'result' => true,
			'data'   => $data
		];
	}

	// PUT
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
		$login = $data['login'];
		$password = $data['password'];

		$result = $this->getPermissionResult($login, $password, ['admin', 'manager']);

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

		$user = User::find()->where(['login' => $login])->one();

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
	public function actionDelete($id, $login, $password)
	{
		$result = $this->getPermissionResult($login, $password, ['admin']);

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

	private function getPermissionResult($login, $password, $groupArray)
	{
		$user = User::findByLogin($login);

		if (empty($user)) {
			$this->error = "Пользователь $login не найден";
			return false;
		}

		if (!$user->validatePassword($password)) {
			$this->error = "Неверный пароль для логина $login";
			return false;
		}

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