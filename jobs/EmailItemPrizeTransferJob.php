<?php

namespace app\jobs;

use Yii;
use app\models\UserPrize;

class EmailItemPrizeTransferJob extends AbstractPrizeTransferJob
{   
    public function executeJob(UserPrize $prize)
    {
        $user = $prize->getUser();
        if (!$user) {
            return false;
        }

        return Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([Yii::$app->params['adminEmail'] => Yii::$app->params['adminName']])
            ->setSubject("Take a prize!")
            ->setTextBody("You've won a {$prize->getPrize()->name}.")
            ->send();
    }
}