<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\Group;

/**
 * This is the model class for table "tbl_user".
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $name
 * @property string $second_name
 * @property string $last_name
 * @property string $position
 * @property string $workplace
 * @property integer $group_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    private $group = null;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            [['group_id'], 'integer'],
            [['group_id'], 'required', 'on' => 'create'],
            [['login'], 'string', 'max' => 100],
            [['login'], 'unique'],
            [['login'], 'email'],
            [['password', 'access_token', 'position', 'workplace'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['name', 'second_name', 'last_name'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Email',
            'password' => 'Пароль',
            'password_reset_token' => 'Контрольная строка',
            'auth_key' => 'Ключ авторизации',
            'name' => 'Имя',
            'second_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'position' => 'Должность',
            'workplace' => 'Рабочее место',
            'group_id' => 'Группа',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    /* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
          return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by login(email)
     *
     * @param  string $login
     * @return static|null
     */
    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getFio()
    {
        $fio = trim($this->name.' '.$this->second_name.' '.$this->last_name);
        return (empty($fio) ? $this->login : $fio);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
        
            if ($insert) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateAccessToken();
            } else if (empty($this->password)) {
                $user = User::findIdentity($this->id);
                $this->password = $user->password;

                if ($this->login == $user->login) {
                  $this->access_token = $user->access_token;
                  $this->auth_key = $user->auth_key;
                } else {
                  $this->generateAuthKey();
                  $this->generateAccessToken();
                }
                
            } else {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generateAccessToken();
            }

            return true;
        } else {
            return false;
        }
    }
}
