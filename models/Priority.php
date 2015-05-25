<?php

namespace app\models;

use Yii;
use app\models\Order;

/**
 * This is the model class for table "tbl_priority".
 *
 * @property integer $id
 * @property string $code
 * @property integer $name
 *
 * @property Order[] $tblOrders
 */
class Priority extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_priority';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['name'], 'integer'],
            [['code'], 'string', 'max' => 16]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => Yii::t('app', 'Code'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['priority_id' => 'id']);
    }
}
