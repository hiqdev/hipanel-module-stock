<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\models;

use hipanel\base\Model as YiiModel;
use hipanel\base\ModelTrait;
use hipanel\modules\stock\Module;
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
        $limitAttributes = $this->getLimitTypesAsAttributes();

        return [
            [['id', 'num'], 'integer'],
            [['name', 'descr'], 'string'],
            [['data'], 'safe', 'on' => ['create', 'update', 'copy']],
            [['model_ids', 'limits'], 'each', 'rule' => ['integer']],
            [['name'], 'required', 'on' => ['create', 'update']],
            [['name'], 'unique', 'filter' => function (ActiveQuery $query): void {
                $query->andWhere(['ne', 'id', $this->id]);
            }, 'on' => ['create', 'update']],
            [$limitAttributes, 'integer', 'on' => ['create', 'update', 'copy']],
            [$limitAttributes, 'default', 'value' => 0],
            [['id'], 'required', 'on' => ['update', 'delete']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels($this->getSupportedLimitTypes());
    }

    public function getSupportedLimitTypes(): array
    {
        return Yii::$container->get(Module::class)->stocksList;
    }

    public function getLimitTypesAsAttributes()
    {
        return array_keys($this->getSupportedLimitTypes());
    }

    public function getModels()
    {
        return $this->hasMany(Model::class, ['id' => 'model_ids'])->andWhere(['with_counters' => true]);
    }

    public function getStocks()
    {
        return array_keys($this->limits);
    }

    public function afterFind(): void
    {
        parent::afterFind();

        foreach ($this->getSupportedLimitTypes() as $attribute => $label) {
            $limit = [
                'limit' => [
                    $attribute => $this->limits[$attribute]['limit'] ?? null,
                ],
            ];
            $this->setAttribute('data', ArrayHelper::merge($this->data ?? [], $limit));
        }
    }
}
