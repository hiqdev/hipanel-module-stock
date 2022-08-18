import { expect, Page } from "@playwright/test";

export default class PartsPage {

    constructor(private page: Page) {}

    async gotoParts(filter) {
        await this.page.goto(`/stock/part/index?PartSearch[partno]=${filter.partno}&PartSearch[dst_name_in]=${filter.dst_name_in}`);
    }

    async setSerialAsPartId() {
        const id = await this.page.locator('#set-serial-form input[id*=id]').inputValue();
        await this.page.locator('#set-serial-form div[class*=serial] input').fill(id);
        await this.page.locator('text=Submit').click();
        await expect(this.page.locator(`td:has-text("${id}")`)).toHaveCount(1);
    }

    async tryToSetTwoRealSerialsForOnePart(realSerial: string) {
        await this.page.locator('textarea[id*=serials]').fill(`${realSerial}, ${realSerial}`);
        await this.page.locator('text=Save').click();
        await expect(this.page.locator('text=Serial numbers should have been put in the same amount as the selected parts')).toHaveCount(1);
    }

    async setRealSerial(realSerial: string) {
        await this.page.locator('textarea[id*=serials]').fill(`${realSerial}`);
        await this.page.locator('text=Save').click();
        await expect(this.page.locator(`td:has-text("${realSerial}")`)).toHaveCount(1);
    }
}