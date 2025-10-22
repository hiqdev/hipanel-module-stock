<?php

declare(strict_types=1);


namespace hipanel\modules\stock\models;

use hipanel\base\Model as YiiModel;
use hipanel\base\ModelTrait;
use hipanel\modules\stock\repositories\StockRepository;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ModelGroup
 *
 * @property int $id
 * @property string $name
 * @property string $descr
 * @property int[] $limits
 * @property-read mixed $stocks
 * @property-read mixed $limitTypesAsAttributes
 * @property-read array $stockList
 * @property-read mixed $models
 * @property int[] $model_ids
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
class ModelGroup extends YiiModel
{
    use ModelTrait;

    public static function tableName()
    {
        return 'modelgroup';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $stockAliasList = $this->getStockList();

        return [
            [['id', 'num'], 'integer'],
            [['name', 'descr'], 'string'],
            [['data'], 'safe', 'on' => ['create', 'update', 'copy']],
            [['model_ids', 'limits'], 'each', 'rule' => ['integer']],
            [['stock_limits'], 'safe'],
            [['name'], 'required', 'on' => ['create', 'update']],
            [$stockAliasList, 'integer', 'on' => ['create', 'update', 'copy']],
            [['id'], 'required', 'on' => ['update', 'delete']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        $list = $this->getStockList();

        return $this->mergeAttributeLabels(array_combine($list, $list));
    }

    public function getStockList(): array
    {
        return Yii::$container->get(StockRepository::class)->getAllAliases();
    }

    public function getModels(): ActiveQuery
    {
        return $this->hasMany(Model::class, ['id' => 'model_ids'])->andWhere(['with_counters' => true]);
    }

    public function getStocks(): array
    {
        return array_keys($this->limits);
    }

    public function afterFind(): void
    {
        parent::afterFind();

        $stockList = $this->getStockList();
        foreach ($stockList as $alias) {
            $limit = [
                'limit' => [
                    $alias => $this->limits[$alias]['limit'] ?? null,
                ],
            ];
            $this->setAttribute('data', ArrayHelper::merge($this->data ?? [], $limit));
        }
    }
}
