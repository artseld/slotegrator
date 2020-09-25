<?php

namespace app\commands;

use app\models\Prize;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

class InitDbDataController extends Controller
{
    /**
     * @return int Exit code
     */
    public function actionIndex()
    {
        $model = User::find()->where(['username' => 'test1'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'test1';
            $user->email = 'test1@test.com';
            $user->role = User::ROLE_USER;
            $user->setPassword('test1');
            $user->generateAuthKey();
            if ($user->save()) {
                echo "test1 done!\n";
            } else {
                echo "test1 FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        $model = User::find()->where(['username' => 'test2'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'test2';
            $user->email = 'test2@test.com';
            $user->setPassword('test2');
            $user->generateAuthKey();
            if ($user->save()) {
                echo "test2 done!\n";
            } else {
                echo "test2 FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        $model = User::find()->where(['username' => 'admin'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'admin';
            $user->email = 'admin@test.com';
            $user->role = User::ROLE_ADMIN;
            $user->setPassword('admin');
            $user->generateAuthKey();
            if ($user->save()) {
                echo "admin done!\n";
            } else {
                echo "admin FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        $model = Prize::find()->where(['name' => 'Money1'])->one();
        if (empty($model)) {
            $prize = new Prize();
            $prize->name = 'Money1';
            $prize->type = Prize::TYPE_MONEY;
            $prize->parameters = [ 'from' => 100, 'to' => 200 ];
            $prize->count = 5;
            if ($prize->save()) {
                echo "prize1 done!\n";
            } else {
                echo "prize1 FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        $model = Prize::find()->where(['name' => 'Points1'])->one();
        if (empty($model)) {
            $prize = new Prize();
            $prize->name = 'Points1';
            $prize->type = Prize::TYPE_POINTS;
            $prize->parameters = [ 'from' => 200, 'to' => 400 ];
            if ($prize->save()) {
                echo "prize2 done!\n";
            } else {
                echo "prize2 FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        $model = Prize::find()->where(['name' => 'Item1'])->one();
        if (empty($model)) {
            $prize = new Prize();
            $prize->name = 'Item1';
            $prize->type = Prize::TYPE_ITEM;
            $prize->count = 10;
            if ($prize->save()) {
                echo "prize3 done!\n";
            } else {
                echo "prize3 FAIL!\n";
                return ExitCode::TEMPFAIL;
            }
        }

        return ExitCode::OK;
    }
}
