import { Page } from "@playwright/test";
import PartForm from "@hipanel-module-stock/helper/PartForm";

export default class PartReplaceView {
    private page: Page;
    private partForm: PartForm;

    constructor(page: Page) {
        this.page = page;
        this.partForm = new PartForm(page);
    }

    public async fillReplaceForm(replaceData: { serialno: string }[]) {
        let key = 0;
        for (const data of replaceData) {
            await this.partForm.fillSerial(data.serialno, key);
            key++;
        }
    }

    public async save() {
        await this.partForm.save();
    }
}