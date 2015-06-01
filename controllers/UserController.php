<?php

namespace app\controllers;

use Yii;
use app\models\Group;
use app\models\User;
use app\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $modelClass = 'app\models\User';

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
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else if (Yii::$app->user->identity->group_id != Group::find()->where(['code' => 'admin'])->one()->id) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else if (
            $id != Yii::$app->user->identity->id
            &&
            (
                Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
                ||
                Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
                &&
                (
                    User::findIdentity($id)->group_id == Group::find()->where(['code' => 'manager'])->one()->id
                    ||
                    User::findIdentity($id)->group_id == Group::find()->where(['code' => 'admin'])->one()->id
                )
            )
            /*$id != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'user'])->one()->id == Yii::$app->user->identity->group_id
            ||
            $id != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
            &&
            User::findIdentity($id)->group_id == Group::find()->where(['code' => 'manager'])->one()->id
            ||
            $id != Yii::$app->user->identity->id
            &&
            Group::find()->where(['code' => 'manager'])->one()->id == Yii::$app->user->identity->group_id
            &&
            User::findIdentity($id)->group_id == Group::find()->where(['code' => 'admin'])->one()->id*/
        ) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
                'group' => Group::find()->all()
            ]);
        }
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else if (Group::find()->where(['code' => 'admin'])->one()->id != Yii::$app->user->identity->group_id) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else {
            $model = new User(['scenario' => 'create']);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'groups' => Group::find()->all()
                ]);
            }
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else if (
            $id != Yii::$app->user->identity->id &&
            Group::find()->where(['code' => 'admin'])->one()->id != Yii::$app->user->identity->group_id
        ) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'groups' => Group::find()->all(),
                    'group' => Group::find()->where(['id' => $model->group_id])->one()
                ]);
            }
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
         if (Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException("Доступ запрещен");
        } else if ($id == Yii::$app->user->identity->id) {
            throw new ForbiddenHttpException("Доступ запрещен. Удаление самого себя не возможно");
        } else if (Group::find()->where(['code' => 'admin'])->one()->id != Yii::$app->user->identity->group_id) {
            throw new ForbiddenHttpException("Доступ запрещен. Нельзя удалять чужого пользователя");
        } else {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Пользователь не найден');
        }
    }
}
