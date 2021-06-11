<?php
declare(strict_types=1);

namespace hipanel\modules\stock\tests\_support\Page\modelGroup;

use hipanel\tests\_support\Page\IndexPage;
use hipanel\tests\_support\Page\Widget\Input\Input;
use hipanel\tests\_support\Step\Acceptance\Manager;

class Index extends IndexPage
{
    public function checkFilterByName(string $value): void
    {
        parent::checkFilterBy(
            new Input($this->tester, "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]"),
            $value,
        );
    }

    /**
     * @param Manager $I
     * @param string $lastOperations
     * @return int
     * @throws \Codeception\Exception\ModuleException
     */
    public function filterModelsByNameAndSelectThem(Manager $I, string $name): int
    {
        $this->filterBy(new Input($I, "//input[contains(@name, 'ModelGroupSearch[name_ilike]')]"), $name);
        $count = $this->countRowsInTableBody();
        foreach (range(1, $count) as $i) {
            $this->selectTableRowByNumber($i);
        }
        return $count;
    }
}
