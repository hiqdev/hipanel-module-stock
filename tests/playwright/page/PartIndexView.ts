import { expect, Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";
import { Modal } from "@hipanel-core/shared/ui/components";

export default class PartIndexView {
  private index: Index;

  constructor(readonly page: Page) {
    this.index = new Index(page);
  }

  async navigateCommon() {
    await this.page.goto("/stock/part/index?representation=common");
  }

  async applyFilters(filters: Array<{ name: string; value: string }>) {
    for (const filter of filters) {
      await this.index.advancedSearch.setFilter(filter.name, filter.value);
    }

    await this.index.advancedSearch.search();
  }

  async filterBySerial(serial: string) {
    await this.index.advancedSearch.applyFilter("serial_ilike", serial);
  }

  async getColumnValue(columnName: string, row: number = 1): Promise<string> {
    return await this.index.getValueInColumnByNumberRow(columnName, row);
  }

  async filterDeletedBySerial(serial: string) {
    // Yii renders the "show_deleted" checkbox alongside a same-named hidden
    // input, so the generic name-based filter locator matches two elements.
    await this.index.advancedSearch.setFilter("serial_ilike", serial);
    await this.page.getByLabel("Show Deleted Parts").check();
    await this.index.submitSearchButton();
  }

  async selectPartsToReplace(count: number) {
    await this.selectRows(count);
    await this.index.clickDropdownBulkButton("Bulk actions", "Replace");
  }

  async selectRows(count: number) {
    await this.index.chooseRangeOfRowsOnTable(1, count);
  }

  async confirmReplacement() {
    await this.confirmReplacementNotification();
    await this.seeReplacementInGrid();
  }

  async confirmReplacementNotification() {
    await this.index.hasNotification("Part has been replaced");
  }

  async seeReplacementInGrid() {
    // await expect(this.page.getByRole('link', { name: 'TRASH_RMA' }).first()).toBeVisible(); // todo: uncomment when HP-2811
    await expect(this.page.getByRole('link', { name: 'REPLACE', exact: true })).toBeVisible();
  }

  async deleteItemOnTable(number: number) {
    await this.chooseNumberRowOnTable(number);

    await this.page.getByRole("button", { name: "Delete" }).click();

    this.page.on("dialog", async dialog => await dialog.accept());
    await this.index.hasNotification("Part has been deleted");
  }

  async bulkMarkAsDeleted(count: number) {
    await this.selectRows(count);
    await this.index.clickDropdownBulkButton("Bulk actions", "Mark as Deleted");

    await this.confirmBulkModalAction("Delete");
    await this.index.hasNotification("Part has been deleted");
  }

  async bulkErase(count: number) {
    await this.selectRows(count);
    await this.index.clickDropdownBulkButton("Bulk actions", "Erase");

    await this.confirmBulkModalAction("Erase");
    await this.index.hasNotification("Part has been erased");
  }

  private async confirmBulkModalAction(buttonText: string) {
    const modal = new Modal(this.page);
    await modal.waitForOpen();
    await modal.clickButton(buttonText);
  }

  async chooseNumberRowOnTable(number: number) {
    await this.index.chooseNumberRowOnTable(number);
  }

  async seePartWasCreated() {
    const rowNumber = 1;
    await this.index.hasNotification("Part has been created");

    // Ensure the current URL matches expected Move index URL
    await expect(this.page).toHaveURL(/\/stock\/move\/index\?MoveSearch%5Bid%5D=/);

    // Get first row move ID from the index table
    const moveId = await this.index.getRowDataKeyByNumber(rowNumber);
    expect(moveId).not.toBeNull();

    // Wait /stock/part/view page to load
    await this.index.clickColumnOnTable("Parts", rowNumber);
  }

  async openSellModal() {
    await this.index.clickDropdownBulkButton("Sell parts", "Sell parts");
  }

  async navigateSelling() {
    await this.page.goto("/stock/part/index?representation=selling");
  }

  async applyFiltersByBuyer(buyer: string) {
    await this.index.applyFilter("buyer_in", buyer);
    await this.index.submitSearchButton();
  }
}
