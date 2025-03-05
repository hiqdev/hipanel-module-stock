import {expect, Page} from "@playwright/test";
import Index from "@hipanel-core/page/Index";

export default class BillIndexView {
    private page: Page;
    private index: Index;

    constructor(page: Page) {
        this.page = page;
        this.index = new Index(page);
    }

    public async navigate() {
        await this.page.goto('/finance/bill/index');
    }

    public async ensureSellingBillWasCreated(sellData: { descr: string; prices: number[] }) {
        await this.navigate();

        // Apply filter for the description
        await this.index.applyFilter('descr', sellData.descr);

        // Open the row menu of the first bill and select "View"
        await this.index.clickPopoverMenu(1, 'View');

        // Ensure the correct number of parts were sold
        await expect(this.page.locator('tr table tr[data-key]')).toHaveCount(sellData.prices.length);
    }
}
