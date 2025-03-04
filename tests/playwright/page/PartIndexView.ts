import { Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";

export default class PartIndexView {
    private page: Page;
    private index: Index;

    constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    async navigate() {
        await this.page.goto("/stock/part/index?representation=common");
    }

    async applyFilters(filters: Array<{ name: string; value: string }>) {
        for (const filter of filters) {
            await this.index.setFilter(filter.name, filter.value);
        }

        await this.index.submitSearchButton();
    }

    public async selectPartsToReplace(start: number, end: number) {
        await this.index.chooseRangeOfRowsOnTable(start, end);
        await this.index.clickDropdownBulkButton('Bulk actions', 'Replace');
    }

    public async confirmReplacement() {
        await this.index.hasNotification('Part has been replaced');
    }
}
