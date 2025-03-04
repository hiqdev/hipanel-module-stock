import {expect, Page} from "@playwright/test";
import Notification from "@hipanel-core/helper/Notification";

export default class SellModal {
    private page: Page;
    private notification: Notification;

    constructor(page: Page) {
        this.page = page;
        this.notification = new Notification(page);
    }

    public async fillSellWindowFields(sellData) {
        const modal = this.page.locator("div.modal-body[data-action-url$=sell]");
        await modal.locator("select[id$=contact_id]").selectOption({ label: sellData.contact_id });
        await modal.locator("input[name*=time]").fill(sellData.time);
        await modal.locator("textarea[id$=description]").fill(sellData.descr);

        const priceFields = await modal.locator("input[type=text][id^=partsell]").all();
        for (let i = 0; i < sellData.prices.length; i++) {
            await priceFields[i].fill(sellData.prices[i].toString());
        }
    }

    public async confirmSale() {
        await this.page.click("button:text('Sell')");
    }

    public async seePartsWereSold() {
        await this.notification.hasNotification("Parts have been successfully sold.");
    }
}
