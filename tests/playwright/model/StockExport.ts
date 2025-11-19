import { Page } from "@playwright/test";
import Index from "@hipanel-core/page/Index";

export default class StockExport {
  private page: Page;private index: Index;

  constructor(page: Page) {
    this.page = page;
    this.index = new Index(page);
  }

  public async startWith(url: string) {
    await this.page.goto(url);

    await this.page.getByPlaceholder("description").first().fill("test");
    await this.index.submitSearchButton();

    await this.index.testExport();
  }
}
