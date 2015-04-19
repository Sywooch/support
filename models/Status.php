<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_status".
 *
 * @property integer $id
 * @property string $code
 * @property integer $name
 *
 * @property TblOrder[] $tblOrders
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['name'], 'integer'],
            [['code'], 'string', 'max' => 16],
            [['code'], 'unique']
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
    public function getTblOrders()
    {
        return $this->hasMany(TblOrder::className(), ['status_id' => 'id']);
    }
}
