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
use hipanel\modules\finance\models\Sale;
use hipanel\modules\stock\models\query\PartQuery;
use hiqdev\hiart\ActiveQuery;
use Yii;

/**
 * Class Part
 *
 * @property Model $model
 * @property string $currency
 * @property-read array $extractedSerials
 * @property-read mixed $title
 * @property-read ActiveQuery $sale
 * @property-read PartWithProfit[] $profit
 */
class Part extends \hipanel\base\Model
{
    use ModelTrait;

    public const STATE_OK = 'ok';
    public const STATE_DELETED = 'deleted';

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
                    'src_type',
                    'src_type_label',
                    'dst_name',
                    'dst_type',
                    'dst_type_label',
                    'first_move',
                    'order_name',
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
                    'move_descr',
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
                    'type',
                    'state',
                    'selling_currency',
                    'selling_price',
                    'selling_time',
                    'buyer',
                    'order_id',
                    'order_name',
                    'company',
                    'device_location',
                    'warranty_till',
                ],
                'safe',
            ],
            [['sale_id'], 'integer'],
            [['dst_name_in', 'src_name_in'], 'filter', 'filter' => 'trim', 'on' => 'search'],
            [
                ['dst_name_in', 'src_name_in'],
                function ($attribute) {
                    $required = StringHelper::mexplode($this->{$attribute});
                    $searchParams = [
                        'limit' => 'all',
                        'name_inilike' => $this->{$attribute},
                        'show_deleted' => true,
                    ];
                    if (str_contains($attribute, 'dst_name')) {
                        $searchParams['types'] = self::getDestinationSubTypes();
                    }
                    $directions = Move::batchPerform('get-directions', $searchParams);
                    $diff = array_diff($required, ArrayHelper::getColumn($directions, 'name'));
                    if (!empty($diff)) {
                        $this->addError(
                            $attribute,
                            Yii::t('hipanel:stock', "No {0} were found for: {1}", [
                                $this->getAttributeLabel($attribute),
                                implode(', ', $diff)
                            ])
                        );
                    }
                },
                'on' => 'search',
            ],
            [['price'], 'number'],
            [['id', 'company_id', 'dst_id', 'model_id', 'client_id', 'buyer_id', 'last_move_id', 'order_id'], 'integer'],

            // Create and copy
            [['partno', 'src_id', 'dst_id', 'serials', 'move_descr', 'price', 'currency', 'company_id'], 'required', 'on' => ['create', 'copy']],
            [['dst_ids'], 'required', 'when' => function ($model) {
                return empty($model->dst_id);
            }, 'on' => ['create']],
            [['serials'], 'unique', 'on' => ['create', 'copy']],

            // Move by one
            [['id', 'dst_id', 'src_id', 'partno', 'serial', 'move_type'], 'required', 'on' => 'move-by-one'],

            // Trash
            [['id', 'dst_id', 'src_id', 'partno', 'serial', 'move_descr'], 'required', 'on' => 'trash'],

            // Replace
            [['id', 'src_id', 'dst_id', 'move_type', 'serial', 'partno'], 'required', 'on' => 'replace'],
            [['serial'], 'unique', 'on' => 'replace'],
            [
                ['serial'],
                'match',
                'pattern' => '/^([a-zA-Z0-9-]+|_)$/u',
                'on' => 'replace',
                'message' => Yii::t('hipanel:stock', 'The input format must match /^[a-zA-Z0-9-]+$/ or the _ character, which will create a random serial.'),
            ],
            [['disposal_id'], 'string', 'on' => 'replace'],

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

            // Set real serials
            [['ids', 'serials'], 'required', 'on' => 'set-real-serials'],
            [['serials'], 'validateRealSerials', 'on' => 'set-real-serials'],

            // Unique serial for update, set-serial
            [['serial'], 'unique', 'on' => ['set-serial', 'update'], 'when' => function ($model) {
                if ($model->isAttributeChanged('serial')) {
                    return static::findOne($model->id)->serial !== $model->serial;
                }

                return false;
            }],

            // Update Order No.
            [['order_id', 'first_move_id'], 'required', 'on' => 'update-order-no'],
            [['id', 'first_move_id'], 'integer', 'on' => 'update-order-no'],
            [['order_id'], 'string', 'on' => 'update-order-no'],

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
            'order_name' => Yii::t('hipanel:stock', 'Order'),
            'order_data' => Yii::t('hipanel:stock', 'Order'),
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
            'price' => Yii::t('hipanel:stock', 'Purchase price'),
            'order_id' => Yii::t('hipanel:stock', 'Order'),
            'device_location' => Yii::t('hipanel:stock', 'DC location'),
            'disposal_id' => Yii::t('hipanel:stock', 'Disposal'),
        ]);
    }

    public function validateRealSerials(string $attribute): void
    {
        if (count($this->getExtractedSerials()) !== count($this->ids ?? [])) {
            $this->addError($attribute, Yii::t('hipanel:stock', 'Serial numbers should have been put in the same amount as the selected parts'));
        }
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
            'move-by-one' => 'move',
            'change-model'=> 'update',
        ];
    }

    public function isTrashed(): bool
    {
        return !empty($this->dst_name) && in_array(mb_strtolower($this->dst_name), ['trash', 'trash_rma'], true);
    }

    public function isDeletable(): bool
    {
        return $this->first_move_id === $this->last_move_id;
    }

    public function getProfit(): ActiveQuery
    {
        return $this->hasMany(PartWithProfit::class, ['id' => 'id'])->indexBy('currency');
    }

    public function getSale(): ActiveQuery
    {
        return $this->hasOne(Sale::class, ['id' => 'object_id']);
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

    public function getTitle()
    {
        return sprintf('%s %s %s #%s', Yii::t('hipanel:stock', $this->model_type_label),
            Yii::t('hipanel:stock', $this->model_brand_label),
            Yii::t('hipanel:stock', $this->partno),
            Yii::t('hipanel:stock', $this->serial)
        );
    }

    public static function getDestinationBasicTypes()
    {
        return array_keys(Ref::getList('type,device'));
    }

    public static function getDestinationSubTypes($subType = null)
    {
        $types = Ref::getList("type,device", null, ['with_recursive' => true]);
        foreach ($types as $type => $name) {
            if (strpos($type, ",") !== false) {
                [$base, $type] = explode(",", $type, 2);
                $type = self::getRecursiveSubType($type);
                $baseSubTypes[$base][] = $type;
            }
            $subTypes[] = $type;
        }

        if (empty($subType)) {
            return $subTypes;
        }

        return empty($baseSubTypes[$subType]) ? [] : $baseSubTypes[$subType];
    }

    public static function getRecursiveSubType($type)
    {
        if (str_contains($type, ",")) {
            $type = explode(",", $type);
            return end($type);
        }

        return $type;
    }

    public function getExtractedSerials(): array
    {
        return preg_split("/[\s,;]+/", $this->serials ?? []);
    }

    public static function find(): PartQuery
    {
        return new PartQuery(static::class);
    }
}
