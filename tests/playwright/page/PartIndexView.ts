import {expect, Page} from "@playwright/test";
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

    public async deleteItemOnTable(number: number) {
        await this.chooseNumberRowOnTable(number);

        await this.page.getByRole('button', { name: 'Delete' }).click();

        this.page.on('dialog', async dialog => await dialog.accept());
        await this.index.hasNotification('Part has been deleted');
    }

    public async chooseNumberRowOnTable(number: number) {
        await this.index.chooseNumberRowOnTable(number);
    }

    public async seePartWasCreated() {
        const rowNumber = 1;
        await this.index.hasNotification('Part has been created');
        await this.index.closeNotification();

        // Ensure the current URL matches expected Move index URL
        await expect(this.page).toHaveURL(/\/stock\/move\/index\?MoveSearch%5Bid%5D=/);

        // Get first row move ID from the index table
        const moveId = await this.index.getRowDataKeyByNumber(rowNumber);
        expect(moveId).not.toBeNull();

        await this.index.clickColumnOnTable('Parts', rowNumber);
    }
}
