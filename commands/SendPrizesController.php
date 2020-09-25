<?php

namespace app\commands;

use app\jobs\BankAccountPrizeTransferJob;
use app\jobs\EmailItemPrizeTransferJob;
use app\jobs\LoyaltyPointsPrizeTransferJob;
use app\models\Prize;
use app\models\UserPrize;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

class SendPrizesController extends Controller
{
    const LIMIT = 10;

    public function actionMoney($limit = self::LIMIT)
    {
        echo "Sending up to ${limit} money prizes...\n";

        $userPrizes = UserPrize::getProcessableList(Prize::TYPE_MONEY, $limit);
        foreach ($userPrizes as $userPrize) {
            Yii::$app->queue->push(new BankAccountPrizeTransferJob([ 'userPrizeId' => $userPrize->id ]));
        }

        return ExitCode::OK;
    }

    public function actionItems($limit = self::LIMIT)
    {
        echo "Sending up to ${limit} item prizes...\n";
        
        $userPrizes = UserPrize::getProcessableList(Prize::TYPE_ITEM, $limit);
        foreach ($userPrizes as $userPrize) {
            Yii::$app->queue->push(new EmailItemPrizeTransferJob([ 'userPrizeId' => $userPrize->id ]));
        }

        return ExitCode::OK;
    }

    public function actionPoints($limit = self::LIMIT)
    {
        echo "Sending up to ${limit} points prizes...\n";

        $userPrizes = UserPrize::getProcessableList(Prize::TYPE_POINTS, $limit);
        foreach ($userPrizes as $userPrize) {
            Yii::$app->queue->push(new LoyaltyPointsPrizeTransferJob([ 'userPrizeId' => $userPrize->id ]));
        }

        return ExitCode::OK;
    }
}
