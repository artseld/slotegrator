<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Prize extends ActiveRecord
{
    const TYPE_MONEY = 'money';
    const TYPE_POINTS = 'points';
    const TYPE_ITEM = 'item';

    public static function tableName()
    {
        return 'prize';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return Prize|null
     */
    public static function getAvailableRandom()
    {
        return self::find()
            ->where('count > :count1', [':count1' => 0])
            ->orWhere('count IS :count2', [':count2' => null])
            ->orderBy('RAND()')
            ->one();
    }
}
