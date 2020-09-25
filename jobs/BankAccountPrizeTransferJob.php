<?php

namespace app\jobs;

use app\models\UserPrize;

class BankAccountPrizeTransferJob extends AbstractPrizeTransferJob
{
    public $userPrizeId;
    
    public function executeJob(UserPrize $prize)
    {
        // TODO Process prize to bank account
        return true;
    }
}