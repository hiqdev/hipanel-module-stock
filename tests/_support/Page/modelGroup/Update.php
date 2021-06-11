<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\_support\Page\modelGroup;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class Update extends Authenticated
{
    /**
     * @param Manager $I
     * @param int $count
     * @param string $action
     * @throws \Codeception\Exception\ModuleException
     */
    public function fillInputAndPressSaveButton(Manager $I, int $count, string $action): void
    {
        foreach (range(0, $count - 1) as $i) {
            (new Input($I, "//input[@name='ModelGroup[$i][name]']"))
                ->setValue($action . '_' . $i);
        }
        $I->pressButton('Save');
        $I->waitForPageUpdate();
    }
}
