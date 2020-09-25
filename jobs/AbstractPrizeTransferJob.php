<?php

namespace app\jobs;

use app\models\UserPrize;
use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

abstract class AbstractPrizeTransferJob extends BaseObject implements JobInterface
{
    public $userPrizeId;

    public function execute($queue)
    {
        $userPrize = UserPrize::findOne($this->userPrizeId);
        if (!$userPrize || !$userPrize->isProcessable()) {
            return;
        }
    
        $userPrize->status = UserPrize::STATUS_PENDING;
        $userPrize->save();
    
        $processed = $this->executeJob($userPrize);

        if ($processed) {
            $userPrize->status = UserPrize::STATUS_DELIVERED;
            $userPrize->save();
        } else {
            Yii::warning('Can not execute job: ' . serialize($queue));
        }
    }

    abstract public function executeJob(UserPrize $userPrize);
}