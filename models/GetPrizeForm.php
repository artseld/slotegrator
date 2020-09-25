<?php

namespace app\models;

use Yii;
use yii\base\Model;

class GetPrizeForm extends Model
{
    public $userId;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // userId required
            [['userId'], 'required'],
            // userId is integer
            ['userId', 'integer'],
        ];
    }

    /**
     * @return bool|null|UserPrize
     */
    public function get()
    {
        if ($this->validate()) {
            $prize = Prize::getAvailableRandom();
            if (!$prize) {
                return null;
            }
            $userPrize = new UserPrize();
            $userPrize->user_id = $this->userId;
            $userPrize->prize_id = $prize->id;
            $userPrize->type = $prize->type; 
            $userPrize->value = $prize->parameters
                ? mt_rand($prize->parameters['from'], $prize->parameters['to'])
                : 1;
            $userPrize->save();
            if ($prize->count) {
                $prize->count--;
                $prize->save(); 
            }
            return $userPrize;
        }
        return false;
    }
}
