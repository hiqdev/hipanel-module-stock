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

use hipanel\base\ModelTrait;
use hipanel\helpers\ArrayHelper;
use hipanel\helpers\StringHelper;
use hipanel\models\Ref;
use Yii;

/**
 * Class Part
 *
 * @property Model $model
 */
class Part extends \hipanel\base\Model
{
    use ModelTrait;

    /** @inheritdoc */
    public static $i18nDictionary = 'hipanel:stock';

    public function rules()
    {
        return [
            [
                [
                    'model_label',
                    'model_type_label',
                    'model_type',
                    'model_brand',
                    'model_brand_label',
                    'src_name',
                    'dst_name',
                    'order_no',
                    'order_data',
                    'dst_ids',
                    'model_ids',
                    'reserve',
                    'serial',
                    'serials',
                    'partno',
                    'create_time',
                    'place',
                    'count',
                    'move_type',
                    'move_type_label',
                    'move_time',
                    'move_descr', 'move_descr_orig',
                    'move_remote_ticket',
                    'remote_ticket',
                    'move_hm_ticket',
                    'hm_ticket',
                    'move_remotehands_label',
                    'remotehands',
                    'show_deleted',
                    'show_groups',
                    'limit',
                    'descr',
                    'price',
                    'currency',
                    'client',
                    'supplier',
                    'order_no',
                    'type',
                    'state',
                    'selling_currency',
                    'selling_price',
                    'selling_time',
                    'buyer',
                ],
                'safe',
            ],
            [['price'], 'number'],
            [['id', 'company_id', 'dst_id', 'model_id', 'client_id', 'buyer_id', 'last_move_id'], 'integer'],

            // Create and copy
            [['partno', 'src_id', 'dst_id', 'serials', 'move_descr', 'move_type', 'price', 'currency', 'company_id'], 'required', 'on' => ['create', 'copy']],
            [['dst_ids'], 'required', 'when' => function ($model) {
                return empty($model->dst_id);
            }, 'on' => ['create']],
            [['serials'], 'unique', 'on' => ['create', 'copy']],

            // Move by one
            [['id', 'dst_id', 'src_id', 'partno', 'serial'], 'required', 'on' => 'move-by-one'],

            // Trash
            [['id', 'dst_id', 'src_id', 'partno', 'serial', 'order_no'], 'required', 'on' => 'trash'],

            // Replace
            [['id', 'src_id', 'dst_id', 'move_type'], 'required', 'on' => 'replace'],

            // Repair
            [['id', 'src_id', 'dst_id', 'move_type'], 'required', 'on' => 'repair'],

            // Update
            [['id', 'model_id', 'serial', 'price', 'currency', 'company_id'], 'required', 'on' => 'update'],

            // Move / Bulk-move
            [['src_id', 'dst_id', 'type'], 'required', 'on' => 'move'],

            // Reserve / Unreserve
            [['id'], 'required', 'on' => 'unreserve'],
            [['id', 'reserve'], 'required', 'on' => 'reserve'],

            // Bulk set price
            [['id', 'price'], 'required', 'on' => 'set-price'],

            // Set serial
            [['id', 'serial'], 'required', 'on' => 'set-serial'],
            [['serial'], 'filter', 'filter' => 'trim', 'on' => 'set-serial'],

            // Unique serial for update, set-serial
            [['serial'], 'unique', 'on' => ['set-serial', 'update'], 'when' => function ($model) {
                if ($model->isAttributeChanged('serial')) {
                    return static::findOne($model->id)->serial !== $model->serial;
                }

                return false;
            }],

            // Update Order No.
            [['order_no', 'first_move_id'], 'required', 'on' => 'update-order-no'],
            [['id', 'first_move_id'], 'integer', 'on' => 'update-order-no'],
            [['order_no'], 'string', 'on' => 'update-order-no'],

            // Change model
            [['id', 'model_id'], 'required', 'on' => 'change-model'],

            // Delete
            [['id'], 'required', 'on' => ['delete']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'partno' => Yii::t('hipanel:stock', 'Part No.'),
            'model_id' => Yii::t('hipanel:stock', 'Part No.'),
            'model_brand' => Yii::t('hipanel:stock', 'Manufacturer'),
            'model_brands' => Yii::t('hipanel:stock', 'Manufacturers'),
            'model_brand_label' => Yii::t('hipanel:stock', 'Manufacturer'),
            'model_type' => Yii::t('hipanel', 'Type'),
            'model_types' => Yii::t('hipanel', 'Types'),
            'model_type_label' => Yii::t('hipanel', 'Type'),
            'create_time' => Yii::t('hipanel', 'Created'),
            'create_date' => Yii::t('hipanel', 'Created'),
            'move_date' => Yii::t('hipanel:stock', 'Moved'),
            'move_time' => Yii::t('hipanel:stock', 'Moved'),
            'move_type_label' => Yii::t('hipanel:stock', 'Move type'),
            'move_descr' => Yii::t('hipanel:stock', 'Move description'),
            'move_type' => Yii::t('hipanel:stock', 'Type'),
            'order_data' => Yii::t('hipanel:stock', 'Order'),
            'order_no' => Yii::t('hipanel:stock', 'Order No.'),
            'src_id' => Yii::t('hipanel:stock', 'Source'),
            'src_name' => Yii::t('hipanel:stock', 'Source'),
            'dst_id' => Yii::t('hipanel:stock', 'Destination'),
            'dst_ids' => Yii::t('hipanel:stock', 'Destination'),
            'dst_name' => Yii::t('hipanel:stock', 'Destination'),
            'supplier' => Yii::t('hipanel:stock', 'Supplier'),
            'currency' => Yii::t('hipanel:stock', 'Currency'),
            'dc_ticket' => Yii::t('hipanel:stock', 'DC ticket'),
            'company_id' => Yii::t('hipanel:stock', 'Company'),
            'buyer' => Yii::t('hipanel:stock', 'Buyer'),
            'buyer_id' => Yii::t('hipanel:stock', 'Buyer'),
            'selling_currency' => Yii::t('hipanel:stock', 'Selling currency'),
            'selling_price' => Yii::t('hipanel:stock', 'Selling price'),
            'selling_time' => Yii::t('hipanel:stock', 'Selling time'),
            'price' => Yii::t('hipanel:stock', 'Purchase price')
        ]);
    }

    public function transformToSymbols($currencyCodes = [])
    {
        $result = [];
        foreach ($currencyCodes as $code) {
            $result[] = StringHelper::getCurrencySymbol($code);
        }

        return $result;
    }

    public function getModel()
    {
        return $this->hasOne(Model::class, ['id' => 'model_id']);
    }

    public function scenarioActions(): array
    {
        return [
            'repair' => 'move',
            'copy' => 'create',
            'trash' => 'move',
            'replace' => 'move',
            'move-by-one' => 'move',
        ];
    }

    public function isDeletable(): bool
    {
        return $this->first_move_id === $this->last_move_id;
    }

    /**
     * @param $types array
     * @param $scenario string
     * @return array
     */
    public function filterTypes($types, $scenario)
    {
        $result = [];
        $matches = [
            'copy' => ['order', 'direct', 'outdated'],
            'trash' => ['died', 'outdated'],
        ];

        if (key_exists($scenario, $matches)) {
            foreach ($matches[$scenario] as $key) {
                $result[$key] = $types[$key];
            }
        } else {
            $result = $types;
        }

        return $result;
    }

    public function getCompanies()
    {
        $companies = Yii::$app->get('cache')->getOrSet([__METHOD__], function () {
            $result = ArrayHelper::map(Ref::find()->where([
                'gtype' => 'type,part_company',
                'select' => 'full',
            ])->all(), 'id', function ($model) {
                return Yii::t('hipanel:stock', $model->label);
            });

            return $result;
        }, 86400 * 24); // 24 days

        return $companies;
    }

    public function getCompany()
    {
        $company = null;
        if ($this->company_id) {
            $company = $this->getCompanies()[$this->company_id];
        }

        return $company;
    }

    public function getTitle()
    {
        return sprintf('%s %s %s #%s', $this->model_type_label, $this->model_brand_label, $this->partno, $this->serial);
    }
}
