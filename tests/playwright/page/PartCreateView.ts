import {expect, Locator, Page} from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";
import SumWithCurrency from "@hipanel-core/input/SumWithCurrency";

export default class PartCreateView {
    private page: Page;

    public constructor(page: Page) {
        this.page = page;
    }

    public async navigate() {
        await this.page.goto('/stock/part/create');
    }

    public async fillPartFields(partData: any) {
        await Select2.field(this.page, `select[id$=partno]`).setValue(partData.partno);
        await Select2.field(this.page, `select[id$=src_id]`).setValue(partData.src_id);
        await Select2.field(this.page, `select[id$=dst_ids]`).setValue(partData.dst_id);

        await this.page.fill('input[id$=serials]', partData.serials);
        await this.page.fill('input[id$=move_descr]', partData.move_descr);

        await SumWithCurrency.field(this.page, "part", 0).setSumAndCurrency(partData.price, partData.currency);

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
