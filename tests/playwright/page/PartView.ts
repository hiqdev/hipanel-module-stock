import { Page } from "@playwright/test";
import { Alert, Modal } from "@hipanel-core/shared/ui/components";
import DetailMenu from "@hipanel-core/helper/DetailMenu";

export default class PartView {
  private page: Page;
  private alert: Alert;
  private detailMenu: DetailMenu;

  constructor(page: Page) {
    this.page = page;
    this.detailMenu = new DetailMenu(page);
    this.alert = Alert.on(page);
  }

  async markPartAsDeleted() {
    await this.detailMenu.clickDetailMenuItem("Mark as Deleted");
    await this.confirmModalAction('Mark as Deleted');

    await this.alert.hasText("Part has been deleted");
  }

  private async confirmModalAction(action) {
    const modal = new Modal(this.page);
    await modal.clickButton(action);
  }

  async erasePart() {
    await this.detailMenu.clickDetailMenuItem("Erase");
    await this.confirmModalAction('Erase');

    await this.alert.hasText("Part has been erased");
  }
}
