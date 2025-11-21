import { Locator, Page } from "@playwright/test";
import { Alert } from "@hipanel-core/shared/ui/components";
import Select2 from "@hipanel-core/input/Select2";

export default class SellModal {
  private page: Page;
  private alert: Alert;

  constructor(page: Page) {
    this.page = page;
    this.alert = Alert.on(page);
  }

  async fillSellWindowFields(sellData) {
    const modal = this.modalLocator();

    await Select2.field(this.page, "select[id$=contact_id]").setValue(sellData.contact_id);

    await modal.locator("input[name*=time]").pressSequentially(sellData.time);
    await modal.locator("textarea[id$=description]").fill(sellData.descr);
    await modal.locator("select[id$=currency]").selectOption(sellData.currency);

    const priceFields = await modal.locator("div[class$=sell] input[type=text][id^=partsell]")
      .all();
    for (let i = 0; i < sellData.prices.length; i++) {
      await priceFields[i].fill(sellData.prices[i].toString());
    }
  }

  private modalLocator(): Locator {
    return this.page.locator("div.modal-body[data-action-url$=sell]");
  }

  async confirmSale() {
    await this.modalLocator().locator(`button:text('Sell')`).click();
  }

  async seePartsWereSold() {
    await this.alert.hasText("Parts have been successfully sold.");
  }
}
