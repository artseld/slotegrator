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
        if (!$this->userPrize) {
            $this->addError('userPrizeId', 'User prize has not been found.');
        }
        if (!$this->userPrize->isProcessable()) {
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
        switch ($this->userPrize->type) {
            case Prize::TYPE_MONEY:
                Yii::$app->queue->push(new BankAccountPrizeTransferJob([ 'userPrizeId' => $this->userPrize->id ]));
                break;
            case Prize::TYPE_POINTS:
                Yii::$app->queue->push(new LoyaltyPointsPrizeTransferJob([ 'userPrizeId' => $this->userPrize->id ]));
                break;
            case Prize::TYPE_ITEM:
                Yii::$app->queue->push(new EmailItemPrizeTransferJob([ 'userPrizeId' => $this->userPrize->id ]));
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
        if ($this->userPrize->type != Prize::TYPE_MONEY) {
            return false;
        }
        $this->userPrize->value = round($this->userPrize->value * ($ratio ?: Yii::$app->params['moneyToPointsRatio']));
        $this->userPrize->type = Prize::TYPE_POINTS;
        return (bool) $this->userPrize->save();
    }

    /**
     * @return bool|null
     */
    private function drop()
    {
        return (bool) $this->userPrize->delete();
    }
}
