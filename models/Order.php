<?php

namespace app\models;

use Yii;

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
    public $userSender;

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
                    //'user_sender',
                    //'user_answer',
                    'priority_id',
                    //'date_finish',
                    'date_deadline',
                    //'date_start',
                    //'status_id',
                    'name',
                    'description',
                    'category_id',
                    //'time_hours',
                    //'complexity'
                ],
                'required'
            ],
            [['user_sender', 'user_answer', 'priority_id', 'status_id', 'category_id', 'time_hours', 'complexity'], 'integer'],
            //[['date_create', 'date_finish', 'date_update', 'date_deadline', 'date_start'], 'safe'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255]
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
            'time_hours' => 'Время/часы',
            'complexity' => 'Сложность/баллы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriority()
    {
        return $this->hasOne(TblPriority::className(), ['id' => 'priority_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TblCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(TblStatus::className(), ['id' => 'status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSender()
    {
        return $this->hasOne(TblUser::className(), ['id' => 'user_sender']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAnswer()
    {
        return $this->hasOne(TblUser::className(), ['id' => 'user_answer']);
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

                $this->userSender = Yii::$app->user->getId();
                $this->status_id = 5;
            }

            return true;
        } else {
            return false;
        }
    }
}
