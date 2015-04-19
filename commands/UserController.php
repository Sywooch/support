<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use app\models\User;

/**
 * This command provide interface for User model
 */
class UserController extends Controller
{
    /**
     * This command create new User.
     * @param string $login the email
     * @param string $pass the password
     * @param string $group_id the group (1 - admin, 2 - manager, 3 - user)
     */
    public function actionAdd($login, $pass, $group_id=1)
    {
        if (empty($login)) {
            die("Empty user email");
        }

        if (empty($pass)) {
            die("Empty user password");
        }

        if ($pass == $login) {
            die("User email and password is equal. It's not secure");
        }

    	$user = new User;
    	$user->login = $login;
        $user->group_id = $group_id;
    	$user->setPassword($pass);
        $user->generateAuthKey();
        
    	$result = $user->save();

    	if ($result) {
            echo "User #".$user->id." add success";
        } else {
            echo "Error add user";
            print_r($user->getErrors());
        }
    }

    public function actionDelete($id)
    {
    	$user = User::find()
    		->where(['id' => $id])
    		->one();
    	
        if (empty($user)) {
            die("User #{$id} not found");
        }

        $result = $user->delete();

    	if ($result) {
            echo "User #{$id} deleted success";
        } else {
            echo "Error delete user #{$id}";
            print_r($user->getErrors());
        }
    }

    public function actionUpdate($id, $param, $value)
    {
    	$user = User::findOne($id);
    	
    	if (empty($user)) {
    		die("User not found");
    	}

		$user->$param = $value;

        $user->generateAuthKey();
    	$result = $user->save();

    	if ($result) {
            echo "User #{$id} updated success";
        } else {
            echo "Error update user #{$id}";
            print_r($user->getErrors());
        }
    }

    public function actionGet($id)
    {
    	$user = User::find()
    		->where(['id' => $id])
    		->one();

    	if (empty($user)) {
    		die("User not found");
    	}

        $login = $user->login;
        $group_id =$user->group_id;

        echo "User [$id]\r\n" .
             "login: $username\r\n";
    }

    public function actionGetList()
    {
        $user = User::find()->all();

        if (empty($user)) {
            die("Users not found");
        }

        echo "id\tlogin\tgroup_id\n\n";
        foreach ($user as $u) {
            echo $u->id."\t".$u->username."\t".$u->group_id."\n";
        }
    }
}
