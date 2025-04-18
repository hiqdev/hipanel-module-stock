<?php

namespace hipanel\modules\stock\models;

use hipanel\base\ModelTrait;
use hipanel\base\Model;
use hipanel\behaviors\File;
use hipanel\models\Ref;
use hipanel\modules\stock\models\query\OrderQuery;
use hipanel\validators\FileValidator;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\db\Query;
use hipanel\modules\stock\helpers\ProfitColumns;

/**
 * @property string $name
 * @property-read PartWithProfit $profitParts
 * @property-read Part[] $parts
 * @property-read OrderWithProfit[] $profit
 */
class Order extends Model
{
    use ModelTrait;

    public const MAX_FILES_COUNT = 5;

    public function behaviors()
    {
        return [
            [
                'class' => File::class,
                'attribute' => 'file',
                'targetAttribute' => 'file_ids',
                'scenarios' => ['create', 'update'],
            ],
        ];
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['id', 'state_id', 'buyer_id', 'seller_id', 'type_id'], 'integer', 'on' => ['create', 'update']],
            [['name', 'no', 'state', 'seller', 'buyer', 'state', 'type'], 'string', 'on' => ['create', 'update']],
            [['state', 'type', 'seller_id', 'buyer_id', 'no', 'time', 'name'], 'required', 'on' => ['create', 'update']],
            [['id'], 'required', 'on' => ['update', 'delete']],
            [['time'], 'datetime', 'format' => 'php:Y-m-d H:i', 'on' => ['create', 'update']],
            [['seller_no', 'company'], 'string'],
            [['company_id'], 'integer'],
            [['name'], 'unique', 'filter' => function (ActiveQuery $query): void {
                $query->andWhere(['ne', 'id', $this->id]);
            }, 'on' => ['create', 'update']],
            ['no', 'unique', 'targetAttribute' => ['no', 'seller_id'],
                'filter' => function (Query $query) {
                    $query->andWhere(['ne', 'id', $this->id]);
                },
                'message' => Yii::t('hipanel.stock.order', 'The combination No. and Reseller has already been taken.'),
                'on' => ['create', 'update']],
            [['file_ids'], 'safe'],
            [['file'], FileValidator::class, 'maxFiles' => self::MAX_FILES_COUNT],
        ]);
    }

    public function attributeLabels()
    {
        return $this->mergeAttributeLabels(array_merge([
            'file' => Yii::t('hipanel.stock.order', 'File attachments'),
            'no' => Yii::t('hipanel.stock.order', '#'),
            'state' => Yii::t('hipanel.stock.order', 'State'),
            'type' => Yii::t('hipanel.stock.order', 'Type'),
            'seller_id' => Yii::t('hipanel.stock.order', 'Seller'),
            'buyer_id' => Yii::t('hipanel.stock.order', 'Buyer'),
            'name' => Yii::t('hipanel.stock.order', 'Order'),
        ], ProfitColumns::getLabels()));
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
        return $this->name;
    }

    public function getParts()
    {
        return $this->hasMany(Part::class, ['order_id' => 'id'])->limit(-1)->orderBy(['move_time' => SORT_DESC]);
    }

    public function getProfit()
    {
        return $this->hasMany(OrderWithProfit::class, ['obj_id' => 'id'])->indexBy('currency');
    }

    public function getPartsProfit()
    {
        return $this->hasMany(PartWithProfit::class, ['id' => 'id']);
    }

    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(\hipanel\models\File::class, ['object_id' => 'id']);
    }

    public function getFileCount(): int
    {
        if ($this->isNewRecord) {
            return 0;
        }

        return count($this->files);
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
