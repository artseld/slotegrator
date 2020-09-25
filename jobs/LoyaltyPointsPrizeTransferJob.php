<?php

namespace app\jobs;

use app\models\UserPrize;

class LoyaltyPointsPrizeTransferJob extends AbstractPrizeTransferJob
{   
    public function executeJob(UserPrize $prize)
    {
        // TODO Process prize to internal account
        return true;
    }
}