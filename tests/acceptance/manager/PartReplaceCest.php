<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\acceptance\manager;

use Codeception\Example;
use hipanel\helpers\Url;
use hipanel\inputs\TextInput;
use hipanel\modules\stock\tests\_support\Page\part\ReplacePart;
use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Dropdown;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

final class PartReplaceCest
{
    public function _before(Manager $I)
    {
        $I->login();
    }

    /**
     * @dataProvider getReplaceData
     */
    public function ensureIndexPageWorks(Manager $I, Example $data): void
    {
        $I->needPage(Url::to('@part/index'));

        $this->selectPartsToReplace($I, $data);
        $this->replaceParts($I, $data);
    }

    private function selectPartsToReplace(Manager $I, Example $data): void
    {
        $page = new IndexPage($I);
        $filters = $data['filters'];
        array_walk(
            $filters,
            fn ($cData, $col) => $page->filterBy($cData['type']::asTableFilter($I, $col), $cData['value'])
        );

        foreach ($data['replaceData'] as $key => $value) {
            $page->selectTableRowByNumber($key + 1);
        }
        $I->click('Bulk actions');
        $I->click('Replace');
        $I->waitForPageUpdate();
    }

    private function replaceParts(Manager $I, Example $data): void
    {
        $replacePage = new ReplacePart($I);
        foreach ($data['replaceData'] as $key => $value) {
            $replacePage->fillPartForm($value, $key);
        }
        $replacePage->pressConfirmButton();
        $replacePage->seePartsWasReplaced();
    }

    protected function getReplaceData(): iterable
    {
        yield [
            'filters' => [
                'Move description' => [
                    'type' => Input::class,
                    'value' => 'test description',
                ],
                'Type' => [
                    'type' => Dropdown::class,
                    'value' => 'cpu',
                ],
            ],
            'replaceData' => [
                ['serialno' => 'test' . uniqid()],
                ['serialno' => 'test' . uniqid()],
            ],
        ];
    }

}

