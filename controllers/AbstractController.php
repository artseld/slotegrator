<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\web\Controller;

abstract class AbstractController extends Controller
{
    /**
     * @return boolean
     */
    protected function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    /**
     * @return boolean
     */
    protected function isUser()
    {
        return Yii::$app->user->getIdentity()->role == User::ROLE_USER;
    }

    /**
     * @return boolean
     */
    protected function isAdmin()
    {
        return Yii::$app->user->getIdentity()->role == User::ROLE_ADMIN;
    }

    /**
     * @return User
     */
    protected function getIdentity()
    {
        return Yii::$app->user->getIdentity();
    }
}
