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

        return $this->render('view', [
            'model'    => $order,
            'category' => Category::find($order->category_id)
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
        } else if (
            Group::find()->where(['code' => 'user'])->one()->id != Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен. Заявки могут создавать только пользователи");
        } else {
            $model = new Order();

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'category' => Category::find()->all(),
                    'priority' => Priority::find()->all()
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
            throw new ForbiddenHttpException("Доступ запрещен. Вы не можете редактировать чужую заявку");
            return;
        } else if (
            !empty($model->user_answer)
            &&
            $model->user_answer != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен. Вы не можете редактировать принятую другим сотрудником заявку");
            return;
        } else if (
            $model->user_sender == Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
            &&
            !empty($model->user_answer)
        ) {
            throw new ForbiddenHttpException("Редактирование заявки запрещено. Заявка уже принята сотрудником технической поддержки");
            return;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'category' => Category::find($model->category_id)->one(),
                'priority' => Priority::find($model->priority_id)->one(),
                'status' => Status::find()->all(),
                'user' => User::find($model->user_sender)
                            ->select(['id', 'login', 'name', 'last_name'])
                            ->one(),

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
