<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\Category;
use app\models\Priority;
use app\models\Status;
use app\models\Group;
use app\models\User;
use app\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use DateTime;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else {
            $searchModel = new OrderSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен для неавторизованных пользователей");
            return;
        }

        $order = $this->findModel($id);

        if ($order->user_sender != Yii::$app->user->identity->id &&
            Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен к чужой заявке пользователя");
            return;
        }

        if (!empty($order->user_answer) &&
            $order->user_answer != Yii::$app->user->identity->id &&
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен к заявке, обрабатываемой другим сотрудником технической поддержки");
            return;
        }

        $order->date_create = (new DateTime(date("Y-m-d H:i:s", strtotime($order->date_create))))->format("d.m.Y H:i");
        $order->date_update = (new DateTime(date("Y-m-d H:i:s", strtotime($order->date_update))))->format("d.m.Y H:i");
        
        if (!empty($order->date_start)) {
            $order->date_start = (new DateTime(date("Y-m-d H:i:s", strtotime($order->date_start))))->format("d.m.Y H:i");
        }

        if (!empty($order->date_finish)) {
            $order->date_finish = (new DateTime(date("Y-m-d H:i:s", strtotime($order->date_finish))))->format("d.m.Y H:i");
        }

        $order->date_deadline = (new DateTime(date("Y-m-d H:i:s", strtotime($order->date_deadline))))->format("d.m.Y H:i");

        return $this->render('view', [
            'model'    => $order,
            'user'     => User::find()->where(['id' => $order->user_sender])->one(),
            'manager'  => User::find()->where(['id' => $order->user_answer])->one(),
            'priority' => Priority::find()->where(['id' => $order->priority_id])->one(),
            'status'   => Status::find()->where(['id' => $order->status_id])->one(),
            'category' => Category::find()->where(['id' => $order->category_id])->one()
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен для неавторизованных пользователей");
            return;
        }

        $allowGroups = ['user', 'admin'];
        $groups = Group::find()
            ->where(['code' => ['user', 'manager', 'admin']])
            ->select(['id', 'code'])
            ->all();
        $allowGroupsArray = [];
        $userGroupsArray  = [];

        foreach ($groups as $group) {
            if (in_array($group->code, $allowGroups)) {
                $allowGroupsArray[] = $group->id;
            }

            $userGroupsArray[$group->id] = $group->code;
        }   

        if (!in_array(Yii::$app->user->identity->group_id, $allowGroupsArray)) {
            throw new ForbiddenHttpException("Доступ запрещен. Заявки могут создавать только пользователи и администраторы");
        } else {
            $model = new Order();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {

                $role = Group::find()->where(['id' => Yii::$app->user->identity->group_id])->one()->code;

                $userSenders = [];
                $userAnswers = [];
                $users = User::find()
                    ->where(['group_id' => array_keys($userGroupsArray)])
                    ->select(['name', 'second_name', 'last_name', 'id', 'group_id', 'login'])
                    ->all();

                foreach ($users as $user) {
                    if (in_array($userGroupsArray[$user->group_id], ['user', 'admin'])) {
                        $userSenders[] = $user;
                    }
                    if (in_array($userGroupsArray[$user->group_id], ['manager', 'admin'])) {
                        $userAnswers[] = $user;
                    }
                }

                return $this->render('create', [
                    'model'         => $model,
                    'categories'    => Category::find()->all(),
                    'priorities'    => Priority::find()->all(),
                    'statuses'      => Status::find()->all(),
                    'userSenders'   => $userSenders,
                    'userAnswers'   => $userAnswers,
                    'role'          => $role
                ]);
            }
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен для неавторизованных пользователей");
            return;
        }

        $model = $this->findModel($id);

        if (
            $model->user_sender != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен. Вы не можете редактировать заявку другого пользователя");
            return;
        } else if (
            !empty($model->user_answer)
            &&
            $model->user_answer != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен. Вы не можете редактировать принятую другим специалистом заявку");
            return;
        } else if (
            $model->user_sender == Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
            &&
            !empty($model->user_answer)
        ) {
            throw new ForbiddenHttpException("Редактирование заявки запрещено. Заявка уже принята специалистом");
            return;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $role = Group::find()->where(['id' => Yii::$app->user->identity->group_id])->one()->code;

            $groups = Group::find()
                ->where(['code' => ['user', 'manager', 'admin']])
                ->select(['id', 'code'])
                ->all();
            $userGroupsArray  = [];

            foreach ($groups as $group) {
                $userGroupsArray[$group->id] = $group->code;
            } 

            $userSenders = [];
            $userAnswers = [];
            $users = User::find()
                ->where(['group_id' => array_keys($userGroupsArray)])
                ->select(['name', 'second_name', 'last_name', 'id', 'group_id', 'login'])
                ->all();

            foreach ($users as $user) {
                if (in_array($userGroupsArray[$user->group_id], ['user', 'admin'])) {
                    $userSenders[] = $user;
                }
                if (in_array($userGroupsArray[$user->group_id], ['manager', 'admin'])) {
                    $userAnswers[] = $user;
                }
            }

            $model->date_deadline = empty($model->date_deadline)
                                    ?
                                    null
                                    :
                                    (new DateTime(date("Y-m-d H:i:s", strtotime($model->date_deadline))))->format("d.m.Y H:i");

            return $this->render('update', [
                'model'      => $model,
                'categories' => Category::find()->all(),
                'priorities' => Priority::find()->all(),
                'statuses'   => Status::find()->all(),
                'category'   => Category::find()->where(['id' => $model->category_id])->one(),
                'priority'   => Priority::find()->where(['id' => $model->priority_id])->one(),
                'status'     => Status::find()->all(),
                'userSenders' => $userSenders,
                'userAnswers' => $userAnswers,
                'userSender' => User::find()
                                ->where(['id' => $model->user_sender])
                                ->select(['id', 'login', 'name', 'last_name', 'second_name'])
                                ->one(),
                'userAnswer' => User::find()
                                ->where(['id' => $model->user_answer])
                                ->select(['id', 'login', 'name', 'last_name', 'second_name'])
                                ->one(),
                'role'       => $role
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Удаление заявки запрещено для неавторизованных пользователей");
            return;
        }

        $model = $this->findModel($id);

        if (
            Group::find()->where(['code' => 'admin'])->one()->id == Yii::$app->user->identity->group_id
            ||
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
            &&
            !empty($model->user_answer)
            &&
            $model->user_answer == Yii::$app->user->identity->id
        ) {
            $model->delete();
            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException("Удаление заявки запрещено");
        }
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Заявка не найдена');
        }
    }
}
