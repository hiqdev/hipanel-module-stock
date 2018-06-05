<?php

namespace hipanel\modules\stock\actions;

use hipanel\actions\ValidateFormAction;
use hipanel\modules\stock\forms\PartBuyoutForm;
use Yii;
use yii\base\DynamicModel;
use yii\helpers\Html;

class ValidateBuyoutFormAction extends ValidateFormAction
{
    public function init()
    {
        $this->setModel(PartBuyoutForm::class);
    }

    public function validateMultiple()
    {
        $result = [];
        foreach ($this->collection->models as $i => $model) {
            $model->validate();
            foreach ($model->getErrors() as $attribute => $errors) {
                if ($attribute !== 'parts') {
                    $id = Html::getInputId($model, $attribute);
                    $result[$id] = $errors;
                } else {
                    foreach ($model->parts as $id => $price) {
                        $validateModel = DynamicModel::validateData(compact('price'), [
                            ['price', 'required', 'message' => Yii::t('hipanel:stock', 'The field cannot be blank.')],
                            [
                                'price', 'number', 'min' => 0, 'max' => 999999,
                                'message' => Yii::t('hipanel:stock', 'The field must be a number.'),
                                'tooSmall' => Yii::t('hipanel:stock', 'The field must be no less than {min}.'),
                                'tooBig' => Yii::t('hipanel:stock', 'The field must be no greater than {max}.'),
                            ],
                        ]);
                        $result['partbuyoutform-parts-' . $id] = $validateModel->getErrors('price');
                    }
                }
            }
        }

        return $result;
    }
}

