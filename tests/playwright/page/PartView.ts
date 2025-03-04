import {Page} from "@playwright/test";
import Notification from "@hipanel-core/helper/Notification";
import DetailMenu from "@hipanel-core/helper/DetailMenu";

export default class PartView {
    private page: Page;
    private notification: Notification;
    private detailMenu: DetailMenu;

    public constructor(page: Page) {
        this.page = page;
        this.notification = new Notification(page);
        this.detailMenu = new DetailMenu(page);
        this.registerAcceptDeleteDialogHandler();
    }

    private registerAcceptDeleteDialogHandler() {
        // By default, dialogs are auto-dismissed by Playwright, so you don't have to handle them
        this.page.on('dialog', async dialog => await dialog.accept());
    }

    public async deletePart()
    {
        await this.detailMenu.clickDetailMenuItem("Delete");
        await this.notification.hasNotification('Part has been deleted');
    }
}
