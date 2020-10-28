<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\_support\Page\part;

use hipanel\tests\_support\Page\Authenticated;
use hipanel\tests\_support\Page\Widget\Input\Input;

class ReplacePart extends Authenticated
{
    public function fillPartForm(array $data, int $index = 0): void
    {
        (new Input($this->tester, "input[id='part-$index-serial']"))
            ->setValue($data['serialno']);
    }

    public function pressConfirmButton(): void
    {
        $this->tester->click('Save');
    }

    public function seePartsWasReplaced(): void
    {
        $this->tester->closeNotification('Part has been replaced');
        $this->tester->seeInCurrentUrl('index');
    }
}
