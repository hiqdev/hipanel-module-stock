<?php declare(strict_types=1);

namespace hipanel\modules\stock\widgets;

use hipanel\modules\stock\models\Part;
use hipanel\modules\stock\repositories\LocationRepository;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

class DisposalField extends Widget
{
    public ActiveForm $form;
    public Part $model;
    public int $index;
    public string $attribute;
    private array $disposalList;

    public function __construct(LocationRepository $disposalRepository, $config = [])
    {
        parent::__construct($config);
        $this->disposalList = $disposalRepository->findForLocation($this->model?->device_location);
    }

    public function init(): void
    {
        parent::init();
        $this->view->registerJs(<<<JS
            (() => {
              $(document).on("change", "[name^='already-disposal']", function (event) {
                const isChecked = $(event.target).is(":checked");
                const disposalElem = $(event.target).parents(".input-group").find("[id$='disposal_id']");
                disposalElem.prop("disabled", function () {
                  $(this).val("");
                  return isChecked;
                });
              });
            })();
JS
            ,
            $this->view::POS_LOAD);

    }

    public function run(): string
    {
        $output = '';
        $output .= $this->form
            ->field($this->model, "[$this->index]disposal_id", [
                'template' => "{label}<div class='input-group'>{ch}{input}</div>\n{hint}\n{error}",
                'parts' => [
                    '{ch}' => Html::checkbox('already-disposal-' . $this->index, false, [
                        'label' => Yii::t('hipanel:stock', 'Already disposed'),
                        'value' => 1,
                        'checked' => $this->model->disposal_id === null,
                        'labelOptions' => ['class' => 'input-group-addon bg-gray'],
                        'class' => 'checkbox-inline',
                        'style' => ['margin-right' => '.5rem'],
                    ]),
                ],
                'inputOptions' => ['disabled' => true],
            ])
            ->dropDownList($this->disposalList, ['prompt' => ''])
            ->label(Yii::t('hipanel:stock', 'Disposal for {0}', Html::tag('mark', $this->model->device_location)));

        return $output;
    }
}
