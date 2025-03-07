import {Page} from "@playwright/test";
import PartForm from "@hipanel-module-stock/helper/PartForm";

export default class PartCreateView {
    private page: Page;
    private partForm: PartForm;

    public constructor(page: Page) {
        this.page = page;
        this.partForm = new PartForm(page);
    }

    public async navigate() {
        await this.page.goto('/stock/part/create');
    }

    public async fillPartFields(partData: any, index: number = 0) {
        await this.partForm.fillPartFields(partData, index);
    }

    public async save() {
        await this.partForm.save();
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
