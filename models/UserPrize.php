<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\db\ActiveRecord;

class UserPrize extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_DELIVERED = 'delivered';

    public static function tableName()
    {
        return 'user_prize';
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

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id'])->one();
    }

    public function getPrize()
    {
        return $this->hasOne(Prize::class, ['id' => 'prize_id'])->one();
    }

    public static function getList($itemsPerPage, $userId = null)
    {
        $query = self::find();
        if ($userId) {
            $query->where([ 'user_id' => $userId ]);
        }

        $pagination = new Pagination([
            'defaultPageSize' => $itemsPerPage,
            'totalCount' => $query->count(),
        ]);

        $prizes = $query->orderBy([ 'created_at' => 'DESC' ])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return [
            'prizes' => $prizes,
            'pagination' => $pagination,
        ];
    }

    public static function getProcessableList($type, $limit)
    {
        return self::find()
            ->where('type = :type', [':type' => $type])
            ->andWhere('(status = :status1 OR (status = :status2 AND updated_at < NOW() - INTERVAL :interval SECOND))',
                [':status1' => self::STATUS_NEW, ':status2' => self::STATUS_PENDING, ':interval' => Yii::$app->params['queueWaitInterval']]
            )
            ->limit($limit)
            ->all();
    }

    public function isProcessable()
    {
        return ($this->status == self::STATUS_NEW
            || ($this->status == self::STATUS_PENDING
                && $this->updated_at < time() - Yii::$app->params['queueWaitInterval']
            ));
    }
}
