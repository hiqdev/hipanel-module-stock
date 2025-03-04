import { expect, Page } from "@playwright/test";
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
        await this.page.pause();
        await this.index.clickBulkButton('Replace');
    }

    public async fillReplaceForm(replaceData: { serialno: string }[], rowNumbers: number[]) {
        for (const [index, data] of replaceData.entries()) {
            await this.page.fill(`table tbody tr:nth-child(${rowNumbers[index]}) input[name='serialno']`, data.serialno);
        }
    }

    public async confirmReplacement() {
        await this.index.clickBulkButton('Peplace');
        await this.index.hasNotification('Parts have been replaced');
    }
}
