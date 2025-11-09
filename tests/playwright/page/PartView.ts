import {Page} from "@playwright/test";
import DetailMenu from "@hipanel-core/helper/DetailMenu";
import { expect } from '@playwright/test';

export default class PartView {
    private page: Page;
    private detailMenu: DetailMenu;
    private id: number;

    public constructor(page: Page, id: number) {
        this.page = page;
        this.detailMenu = new DetailMenu(page);
        this.registerAcceptDeleteDialogHandler();
        this.id = id;
    }

    private registerAcceptDeleteDialogHandler() {
        // By default, dialogs are auto-dismissed by Playwright, so you don't have to handle them
        this.page.on('dialog', async dialog => await dialog.accept());
    }

    public async deletePart()
    {
        await this.detailMenu.clickDetailMenuItem("Delete");
    }

    public async confirmDeletion() {
        await this.goToPartView();

        await expect(this.partStatusLabel()).toHaveText('Deleted');
    }

    public async goToPartView() {
        await this.page.goto(`/stock/part/view?id=${this.id}`);
    }

    private partStatusLabel() {
        return this.page.getByTestId('part-status');
    }
}
