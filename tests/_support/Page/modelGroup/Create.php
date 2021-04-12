<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\_support\Page\modelGroup;

use hipanel\helpers\Url;
use hipanel\tests\_support\Page\Authenticated;

class Create extends Authenticated
{
    public function toPage()
    {
        $this->tester->needPage(Url::to('@model-group/create'));
    }

    public function addAdditionalForms(int $numForms): void
    {
        foreach (range(0, $numForms) as $num) {
            $this->tester->click("//button[contains(@class, 'add-item')]");
        }
    }
}
