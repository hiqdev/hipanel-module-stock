import {expect, Locator, Page} from "@playwright/test";

export default class PartCreateView {
    private page: Page;

    public constructor(page: Page) {
        this.page = page;
    }

    public async navigate() {
        await this.page.goto('/stock/part/create');
    }

    public async fillPartFields(partData: any) {
        await this.page.selectOption('select[id$=partno]', partData.partno);
        await this.page.selectOption('select[id$=src_id]', partData.src_id);
        await this.page.selectOption('select[id$=dst_ids]', partData.dst_id);
        await this.page.fill('input[id$=serials]', partData.serials);
        await this.page.fill('input[id$=move_descr]', partData.move_descr);
        await this.page.fill('input[id$=price]', partData.price.toString());
        await this.page.click(`li a[data-value=${partData.currency}]`);
        await this.page.selectOption('select[id$=company_id]', partData.company_id);
    }

    public async save() {
        await this.page.click('button:has-text("Save")');
    }

    public async addPart() {
        await this.page.click("div.item:last-child button.add-item");
    }

    public async removePart() {
        await this.page.click("div.item:last-child button.remove-item");
    }

    public async copyPart() {
        await this.page.click("div.item:last-child button.copy");
    }
}