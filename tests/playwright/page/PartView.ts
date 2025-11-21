import { Page } from "@playwright/test";
import { Alert } from "@hipanel-core/shared/ui/components";
import DetailMenu from "@hipanel-core/helper/DetailMenu";

export default class PartView {
  private page: Page;
  private alert: Alert;
  private detailMenu: DetailMenu;

  constructor(page: Page) {
    this.page = page;
    this.detailMenu = new DetailMenu(page);
    this.alert = Alert.on(page);
    this.registerAcceptDeleteDialogHandler();
  }

  async deletePart() {
    await this.detailMenu.clickDetailMenuItem("Delete");
    await this.alert.hasText("Part has been deleted");
  }

  private registerAcceptDeleteDialogHandler() {
    // By default, dialogs are auto-dismissed by Playwright, so you don't have to handle them
    this.page.on("dialog", async dialog => await dialog.accept());
  }

}
