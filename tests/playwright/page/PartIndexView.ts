import {expect, Page} from "@playwright/test";
import Index from "@hipanel-core/page/Index";

export default class PartIndexView {
    private page: Page;
    private index: Index;

    constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    public async navigateCommon() {
        await this.page.goto("/stock/part/index?representation=common");
    }

    public async applyFilters(filters: Array<{ name: string; value: string }>) {
        for (const filter of filters) {
            await this.index.setFilter(filter.name, filter.value);
        }

        await this.index.submitSearchButton();
    }

    public async filterBySerial(serial: string) {
        await this.index.applyFilter('serial_ilike', serial);
    }

    public async selectPartsToReplace(count: number) {
        await this.selectRows(count);
        await this.index.clickDropdownBulkButton('Bulk actions', 'Replace');
    }

    public async selectRows(count: number) {
        await this.index.chooseRangeOfRowsOnTable(1, count);
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

    public async seePartWasCreated(): Promise<number> {
        const rowNumber = 1;
        await this.index.hasNotification('Part has been created');
        await this.index.closeNotification();

        // Ensure the current URL matches expected Move index URL
        await expect(this.page).toHaveURL(/\/stock\/move\/index\?MoveSearch%5Bid%5D=/);

        // Get first row move ID from the index table
        const moveId = await this.index.getRowDataKeyByNumber(rowNumber);
        expect(moveId).not.toBeNull();

        // Wait /stock/part/view page to load
        await this.index.clickColumnOnTable('Parts', rowNumber);

        return this.extractPartIdFromUrl();
    }

    private extractPartIdFromUrl(): number {
        const url = this.page.url();
        const urlObj = new URL(url);
        const idParam = urlObj.searchParams.get('id');

        if (!idParam) {
            throw new Error('Part ID not found in URL.');
        }

        return Number(idParam);
    }

    public async openSellModal() {
        await this.index.clickDropdownBulkButton('Sell parts', 'Sell parts');
    }

    public async navigateSelling() {
        await this.page.goto("/stock/part/index?representation=selling");
    }

    public async applyFiltersByBuyer(buyer: string) {
        await this.index.applyFilter('buyer_in', buyer);
        await this.index.submitSearchButton();
    }
}
