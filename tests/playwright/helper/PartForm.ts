import { Page } from "@playwright/test";
import Select2 from "@hipanel-core/input/Select2";
import PriceWithCurrency from "@hipanel-module-stock/input/PriceWithCurrency";

export default class PartForm {
  private page: Page;

  constructor(page: Page) {
    this.page = page;
  }

  async fillPartFields(partData: any, index: number = 0) {
    await Select2.field(this.page, this.selector("select", "partno", index)).setValue(partData.partno);
    await Select2.field(this.page, this.selector("select", "src_id", index)).setValue(partData.src_id);
    await Select2.field(this.page, this.selector("select", "dst_ids", index)).setValue(partData.dst_id);

    await this.fillSerials(partData.serials, index);
    await this.page.fill(this.selector("input", "move_descr", index), partData.move_descr);

    await PriceWithCurrency.field(this.page, "part", index).setSumAndCurrency(partData.price, partData.currency);

    await this.page.selectOption(this.selector("select", "company_id", index), partData.company_id);
  }

  private selector(type: string, name: string, index: number = 0): string {
    return `${type}[id=part-${index}-${name}]`;
  }

  async fillSerials(serial: string, index: number = 0) {
    await this.page.fill(this.selector("input", "serials", index), serial);
  }

  /**
   * It is strange, but in the same form on the /stock/part/replace page "serials" input has "serial" name
   */
  async fillSerial(serial: string, index: number = 0) {
    await this.page.fill(this.selector("input", "serial", index), serial);
  }

  async save() {
    await this.page.click("button:has-text(\"Save\")");
  }
}
