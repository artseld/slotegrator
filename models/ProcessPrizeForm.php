<?php

namespace app\models;

use app\jobs\BankAccountPrizeTransferJob;
use app\jobs\EmailItemPrizeTransferJob;
use app\jobs\LoyaltyPointsPrizeTransferJob;
use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;

class ProcessPrizeForm extends Model
{
    public $userPrizeId;
    
    /**
     * @var UserPrize
     */
    private $userPrize;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // userPrizeId required
            [['userPrizeId'], 'required'],
            // userPrizeId is integer
            ['userPrizeId', 'integer'],
        ];
    }

    public function afterValidate()
    {
        $this->userPrize = UserPrize::findOne($this->userPrizeId);
        if (!$this->getUserPrize()) {
            $this->addError('userPrizeId', 'User prize has not been found.');
        }
        if (!$this->getUserPrize()->isProcessable()) {
            $this->addError('userPrizeId', 'User prize is already in delivering process.');
        }

        $this->trigger(self::EVENT_AFTER_VALIDATE);
    }

    /**
     * @return bool|null
     * @throws NotSupportedException
     */
    public function process($postData)
    {
        if ($this->validate()) {
            if (isset($postData['deliver-button'])) {
                return $this->deliver();
            }
            if (isset($postData['convert-button'])) {
                return $this->convert();
            }
            if (isset($postData['drop-button'])) {
                return $this->drop();
            }
            throw new NotSupportedException('Unsupported operation', 400);
        }
        return false;
    }

    /**
     * @return boolean
     * @throws NotSupportedException
     */
    private function deliver()
    {
        switch ($this->getUserPrize()->type) {
            case Prize::TYPE_MONEY:
                Yii::$app->queue->push(new BankAccountPrizeTransferJob([ 'userPrizeId' => $this->getUserPrize()->id ]));
                break;
            case Prize::TYPE_POINTS:
                Yii::$app->queue->push(new LoyaltyPointsPrizeTransferJob([ 'userPrizeId' => $this->getUserPrize()->id ]));
                break;
            case Prize::TYPE_ITEM:
                Yii::$app->queue->push(new EmailItemPrizeTransferJob([ 'userPrizeId' => $this->getUserPrize()->id ]));
                break;
            default:
                throw new NotSupportedException('Unsupported prize type', 400);
        }
        return true;
    }

    /**
     * @param integer|float|null $ratio
     * @return boolean
     */
    private function convert($ratio = null)
    {
        if ($this->getUserPrize()->type != Prize::TYPE_MONEY) {
            return false;
        }
        $this->getUserPrize()->value = round($this->getUserPrize()->value * ($ratio ?: Yii::$app->params['moneyToPointsRatio']));
        $this->getUserPrize()->type = Prize::TYPE_POINTS;
        return (bool) $this->getUserPrize()->save();
    }

    /**
     * @return bool|null
     */
    private function drop()
    {
        return (bool) $this->getUserPrize()->delete();
    }

    /**
     * @return UserPrize
     */
    public function getUserPrize()
    {
        return $this->userPrize;
    }
}
