<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use app\models\Status;
use DateTime;

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
                ['serial_number'],
                'match',
                'pattern' => '/^[A-Za-z0-9]+$/',
                'message' => 'Серийный номер должен состоять букв латинского алфавита и цифр'
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
            'user_answer' => 'Специалист',
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

                $user = Yii::$app->user;

                if (empty($user->getId())) {
                    $this->addError("user_sender", "Пользователь не авторизован");
                    return false;
                }

                if (empty($this->user_sender)) {
                    $this->user_sender = $user->getId();
                }
                
                if (empty($this->status_id)) {
                    $status = Status::find()->where(['code' => 'new'])->one();

                    if (empty($status)) {
                        $this->addError("status_id", "Статус заявки не определен");
                        return false;
                    }

                    $this->status_id = $status->id;
                }

                $this->date_create = new Expression("NOW()");
                $this->date_update = $this->date_create;
                $this->date_deadline = (new DateTime(date("Y-m-d H:i:s", strtotime($this->date_deadline))))->format("Y-m-d H:i:s");

            } else {

                $user = Yii::$app->user;

                if (empty($user->getId())) {
                    $this->addError("user_sender", "Пользователь не авторизован");
                    return false;
                }

                if (
                    empty($this->user_answer)
                    &&
                    $user->identity->group_id == Group::find()->where(['code' => 'manager'])->one()->id
                ) {
                    $this->user_answer = $user->getId();
                }
                
                $this->date_update = new Expression("NOW()");
                $statusDone = Status::find()->where(['code' => 'done'])->one();

                if ($statusDone->id == $this->status_id) {
                    $this->date_finish = $this->date_update;
                } else {
                    $this->date_finish = null;
                }

                if (
                    empty($this->date_start)
                    &&
                    $user->identity->group_id != Group::find()->where(['code' => 'user'])->one()->id
                ) {
                    $this->date_start = new Expression("NOW()");
                }

                //$this->date_deadline = Yii::$app->formatter->asDate($this->date_deadline, 'php:Y-m-d H:i:s'); // timezone bug
                $this->date_deadline = (new DateTime(date("Y-m-d H:i:s", strtotime($this->date_deadline))))->format("Y-m-d H:i:s");
            }

            return true;
        } else {
            return true;
        }
    }
}
