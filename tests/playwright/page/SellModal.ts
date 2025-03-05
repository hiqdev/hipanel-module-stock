import {expect, Locator, Page} from "@playwright/test";
import Notification from "@hipanel-core/helper/Notification";
import Select2 from "@hipanel-core/input/Select2";

export default class SellModal {
    private page: Page;
    private notification: Notification;

    constructor(page: Page) {
        this.page = page;
        this.notification = new Notification(page);
    }

    public async fillSellWindowFields(sellData) {
        const modal = this.modalLocator();

        await Select2.field(this.page, 'select[id$=contact_id]').setValue(sellData.contact_id);

        await modal.locator("input[name*=time]").fill(sellData.time);
        await modal.locator("textarea[id$=description]").fill(sellData.descr);

        const priceFields = await modal.locator("div[class$=sell] input[type=text][id^=partsell]")
            .all();
        for (let i = 0; i < sellData.prices.length; i++) {
            await priceFields[i].fill(sellData.prices[i].toString());
        }
    }

    private modalLocator(): Locator {
        return this.page.locator("div.modal-body[data-action-url$=sell]");
    }

    public async confirmSale() {
        await this.modalLocator().locator(`button:text('Sell')`).click();
    }

    public async seePartsWereSold() {
        await this.notification.hasNotification("Parts have been successfully sold.");
    }
}
