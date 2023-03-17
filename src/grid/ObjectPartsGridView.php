<?php

declare(strict_types=1);

namespace hipanel\modules\stock\grid;

use yii2mod\query\ArrayQuery;
use hipanel\grid\BoxedGridView;
use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\models\PartSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\i18n\Formatter;

class ObjectPartsGridView extends BoxedGridView
{
    public array $parts;
    public $boxed = false;
    private Formatter $formater;

    public function init()
    {
        $this->filterModel = new PartSearch();
        $query = (new ArrayQuery())->from($this->parts);
        $params = Yii::$app->request->queryParams;
        if ($this->filterModel->load($params) && $this->filterModel->validate()) {
            $query->andFilterWhere(['model_type' => $this->filterModel->model_type]);
            $query->andFilterWhere(['model_brand' => $this->filterModel->model_brand]);
            $query->andFilterWhere(['order_name' => $this->filterModel->order_name]);
            $query->andFilterWhere(['company' => $this->filterModel->company]);
            $query->andFilterWhere(['currency' => $this->filterModel->currency]);

            $query->andFilterWhere(['like', 'serial', $this->filterModel->serial]);
            $query->andFilterWhere(['like', 'move_time', $this->filterModel->move_time]);
        }
        $models = $query->indexBy('id')->all();
        $this->dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'modelClass' => $this->filterModel,
            'pagination' => ['pageSize' => -1],
            'sort' => [
                'attributes' => ['serial', 'move_time', 'price', 'order_name'],
                'defaultOrder' => ['move_time' => SORT_DESC],
            ],
            'key' => fn(Part $part): string => (string)$part->id,
        ]);
        $this->columns = array_keys($this->columns());
        $this->showFooter = false;
        $this->showHeader = false;
        $this->summary = false;
        $this->layout = '{items}';
        $this->tableOptions['class'] = 'table table-condensed table-ultra-condensed';
        $this->tableOptions['style'] = 'margin-bottom: 0;';
        $this->formater = Yii::$app->formatter;
        parent::init();
    }

    public function columns()
    {
        $user = Yii::$app->user;

        return [
            'serial' => [
                'contentOptions' => ['style' => 'width: 30%'],
                'label' => Yii::t('hipanel:stock', 'Serials'),
                'attribute' => 'serial',
                'format' => 'raw',
                'value' => static function (Part $part): string {
                    return Html::a($part->serial, ['@part/view', 'id' => $part->id], ['data-pjax' => 0]);

                },
            ],
            'model_brand' => [
                'contentOptions' => ['style' => 'width: 10%'],
                'label' => Yii::t('hipanel:stock', 'Manufacturer'),
                'attribute' => 'model_brand',
                'value' => static fn(Part $part): string => Yii::t('hipanel:stock', $part->model_brand_label),
                'filter' => $this->dropdownFor('model_brand', 'model_brand_label'),
            ],
            'price' => [
                'contentOptions' => ['style' => 'width: 10%'],
                'label' => Yii::t('hipanel:stock', 'Price'),
                'attribute' => 'price',
                'value' => fn(Part $part): ?string => !empty($part->price) ? $this->formatter->asCurrency($part->price,
                    $part->currency) : null,
                'filter' => $this->dropdownFor('currency'),
                'visible' => $user->can('move.read-all'),
            ],
            'move_time' => [
                'contentOptions' => ['style' => 'width: 20%'],
                'label' => Yii::t('hipanel:stock', 'Sale time'),
                'attribute' => 'move_time',
                'format' => ['datetime', 'php:Y-m-d H:i'],
                'visible' => $user->can('order.read'),
            ],
            'order_name' => [
                'contentOptions' => ['style' => 'width: 20%'],
                'label' => Yii::t('hipanel:stock', 'Order No.'),
                'attribute' => 'order_name',
                'format' => 'raw',
                'value' => static fn(Part $part): ?string => !empty($part->order_name) ? Html::a($part->order_name,
                    ['@order/view', 'id' => $part->order_id],
                    ['data-pjax' => 0]) : null,
                'filter' => $this->dropdownFor('order_name'),
                'visible' => $user->can('order.read'),
            ],
            'company' => [
                'contentOptions' => ['style' => 'width: 10%'],
                'label' => Yii::t('hipanel:stock', 'Company'),
                'attribute' => 'company',
                'value' => 'company',
                'filter' => $this->dropdownFor('company'),
                'visible' => $user->can('part.create'),
            ],
        ];
    }

    private function dropdownFor(string $attributeName, ?string $attributeLabel = null): string|false
    {
        $label = $attributeLabel ?? $attributeName;
        $options = ArrayHelper::map(
            $this->parts,
            $attributeName,
            static fn(Part $part): string => empty($part->{$attributeName}) ?
                '--' : Yii::t('hipanel:stock', $part->{$label})
        );
        if (count($options) === 1) {
            return false;
        }

        return Html::activeDropDownList(
            $this->filterModel,
            $attributeName,
            $options,
            ['class' => 'form-control', 'prompt' => '']
        );
    }
}
