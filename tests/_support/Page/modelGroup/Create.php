<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\_support\Page\modelGroup;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;

class Create extends Authenticated
{
    public function toPage()
    {
        $this->tester->needPage(Url::to('@model-group/create'));
    }

    public function addAdditionalForms(int $numForms): void
    {
        foreach (range(0, $numForms - 1) as $num) {
            $this->tester->click("//button[contains(@class, 'add-item')]");
        }
    }

    public function addModelGroupItem(array $item): void
    {
        $I = $this->tester;
        $i = $item['num'];

        (new Input($I, "//input[@name='ModelGroup[$i][name]']"))
            ->setValue($item['name']);

        (new Input($I, "//textarea[contains(@name, 'ModelGroup[$i][descr]')]"))
            ->setValue($item['description']);

        foreach ($item['stockList'] as $name => $value) {
            (new Input($I, "//input[@name='ModelGroup[$i][data][limit][$name]']"))
                ->setValue($value);
        }
    }

    public function save()
    {
        $I = $this->tester;
        $I->pressButton('Save');
        $I->waitForPageUpdate();
        $I->closeNotification('Created');
        $I->seeInCurrentUrl('/stock/model-group/index');
    }
}
