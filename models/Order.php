<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\Status;

/**
 * This is the model class for table "tbl_order".
 *
 * @property integer $id
 * @property integer $user_sender
 * @property integer $user_answer
 * @property integer $priority_id
 * @property string $date_create
 * @property string $date_finish
 * @property string $date_update
 * @property string $date_deadline
 * @property string $date_start
 * @property integer $status_id
 * @property string $name
 * @property string $description
 * @property integer $category_id
 * @property string $model
 * @property string $serial_number
 * @property string $sender_location
 * @property string $sender_name
 * @property string $sender_position
 * @property integer $time_hours
 * @property integer $complexity
 *
 * @property TblPriority $priority
 * @property TblCategory $category
 * @property TblStatus $status
 * @property TblUser $userSender
 * @property TblUser $userAnswer
 */
class Order extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'user_sender',
                    'user_answer',
                    'priority_id',
                    'status_id',
                    'category_id'
                ],
                'integer'
            ],
            ['complexity', 'integer', 'min' => 1, 'max' => 10],
            ['time_hours', 'integer', 'min' => 1, 'max' => 8760],
            [
                [
                    'date_create',
                    'date_finish',
                    'date_update',
                    'date_deadline',
                    'date_start'
                ],
                'safe'
            ],
            [
                ['description'], 'string'
            ],
            [
                ['name', 'sender_location'], 'string', 'max' => 255
            ],
            [
                ['model', 'serial_number'], 'string', 'max' => 50
            ],
            [
                ['sender_name', 'sender_position'], 'string', 'max' => 100
            ],
            [
                [
                    'priority_id',
                    'category_id',
                    'date_deadline',
                    'name',
                    'description',
                    'sender_location',
                    'sender_name',
                    'sender_position'
                ],
                'required'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_sender' => 'Пользователь',
            'user_answer' => 'Сотрудник',
            'priority_id' => 'Приоритет',
            'date_create' => 'Дата создания',
            'date_finish' => 'Дата завершения',
            'date_update' => 'Дата изменения',
            'date_deadline' => 'Крайний строк',
            'date_start' => 'Дата начала',
            'status_id' => 'Статус',
            'name' => 'Название',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'model' => 'Модель',
            'serial_number' => 'Серийный номер',
            'sender_location' => 'Местоположение',
            'sender_name' => 'ФИО заявителя',
            'sender_position' => 'Должность заявителя',
            'time_hours' => 'Время/часы',
            'complexity' => 'Сложность/баллы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(Priority::className(), ['id' => 'priority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Status::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSender()
    {
        return $this->hasOne(User::className(), ['id' => 'user_sender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAnswer()
    {
        return $this->hasOne(User::className(), ['id' => 'user_answer']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            if ($insert) {

                /*
                print('<pre>');
                print_r($this);
                print('</pre>');
                die();
                */

                $user = Yii::$app->user;

                if (empty($user->getId())) {
                    $this->addError("user_sender", "Пользователь не авторизован");
                    return false;
                }

                $status = Status::find()->where(['code' => 'new'])->one();

                if (empty($status)) {
                    $this->addError("status_id", "Статус заявки не определен");
                    return false;
                }

                $this->user_sender = $user->getId();
                $this->date_create = new Expression("NOW()");
                $this->date_update = $this->date_create;
                $this->status_id = $status->id;

            } else {

                $user = Yii::$app->user;

                if (empty($user->getId())) {
                    $this->addError("user_sender", "Пользователь не авторизован");
                    return false;
                }

                $this->user_answer = $user->getId();
                $this->date_update = new Expression("NOW()");
                
                $statusDone = Status::find()->where(['code' => 'done'])->one();

                if ($statusDone->id == $this->status_id) {
                    $this->date_finish = $this->date_update;
                } else {
                    $this->date_finish = null;
                }

                if (empty($this->date_start)) {
                    $this->date_start = new Expression("NOW()");
                }
            }

            return true;
        } else {
            return true;
        }
    }
}
