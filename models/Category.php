<?php

namespace app\models;

use Yii;
use app\models\Order;

/**
 * This is the model class for table "tbl_category".
 *
 * @property integer $id
 * @property string $name
 *
 * @property TblOrder[] $tblOrders
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTblOrders()
    {
        return $this->hasMany(Order::className(), ['category_id' => 'id']);
    }
}
