<?php
/**
 * hipanel.advancedhosters.com
 *
 * @link      http://hipanel.advancedhosters.com/
 * @package   hipanel.advancedhosters.com
 * @license   proprietary
 * @copyright Copyright (c) 2016-2019, AdvancedHosters (https://advancedhosters.com/)
 */

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;
use hipanel\base\Model;
use hipanel\models\Ref;
use hipanel\modules\stock\models\query\OrderQuery;
use Yii;
use yii\db\Query;

class Order extends Model
{
    use ModelTrait;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'state_id', 'buyer_id', 'seller_id', 'type_id'], 'integer', 'on' => ['create', 'update']],
            [['comment', 'no', 'state', 'seller', 'buyer', 'state', 'type'], 'string', 'on' => ['create', 'update']],
            [['state', 'type', 'seller_id', 'buyer_id', 'no', 'time'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update', 'delete']],
            [['time'], 'datetime', 'format' => 'php:Y-m-d H:i', 'on' => ['create', 'update']],
            [['seller_no'], 'string'],
            ['no', 'unique', 'targetAttribute' => ['no', 'seller_id'],
                'filter' => function (Query $query) {
                    $query->andWhere(['ne', 'id', $this->id]);
                },
                'message' => Yii::t('hipanel.stock.order', 'The combination No. and Reseller has already been taken.'),
                'on' => ['create', 'update']],
        ]);
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'no' => Yii::t('hipanel.stock.order', 'No.'),
            'state' => Yii::t('hipanel.stock.order', 'State'),
            'type' => Yii::t('hipanel.stock.order', 'Type'),
            'buyer_id' => Yii::t('hipanel.stock.order', 'Buyer'),
            'comment' => Yii::t('hipanel.stock.order', 'Comment'),
            'seller_id' => Yii::t('hipanel.stock.order', 'Reseller'),
            'time' => Yii::t('hipanel.stock.order', 'Lead time'),
        ]);
    }

    public function getTypeOptions(): array
    {
        return Ref::getList('type,zorder');
    }

    public function getStateOptions(): array
    {
        return Ref::getList('state,zorder');
    }

    public function getPageTitle()
    {
        return $this->no;
    }

    public function getParts()
    {
        return $this->hasMany(Part::class, ['order_id' => 'id'])->limit(-1)->orderBy(['move_time' => SORT_DESC]);
    }

    /**
     * {@inheritdoc}
     * @return OrderQuery
     */
    public static function find($options = [])
    {
        return new OrderQuery(get_called_class(), [
            'options' => $options,
        ]);
    }
}
