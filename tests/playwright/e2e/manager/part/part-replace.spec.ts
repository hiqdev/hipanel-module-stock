import { test } from "@hipanel-core/fixtures";
import { expect } from "@playwright/test";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";
import PartReplaceView from "@hipanel-module-stock/page/PartReplaceView";
import PartCreateView from "@hipanel-module-stock/page/PartCreateView";
import { UniqueId } from "@hipanel-core/shared/lib";

function futureDateYmd(monthsFromNow: number): string {
  const date = new Date();
  date.setMonth(date.getMonth() + monthsFromNow);
  return date.toISOString().slice(0, 10);
}

const serialNo = UniqueId.generate("test");
const data = {
  filters: [
    {
      name: "move_descr_ilike",
      value: "test description",
    },
    {
      name: "model_types",
      value: "CPU",
    },
  ],
  replaceData: [
    { serialno: serialNo },
  ],
};

test.describe("Part Replacement", () => {
  test("Ensure parts can be replaced @hipanel-module-stock @manager", async ({ page }) => {
    const partIndexPage = new PartIndexView(page);
    const partReplacePage = new PartReplaceView(page);

    await partIndexPage.navigateCommon();
    await partIndexPage.applyFilters(data.filters);
    await partIndexPage.selectPartsToReplace(data.replaceData.length);

    await partReplacePage.fillReplaceForm(data.replaceData);
    await partReplacePage.save();

    await partIndexPage.confirmReplacement();
  });

  test("Ensure warranty_till is copied to the replacement part @hipanel-module-stock @manager", async ({ page }) => {
    // Part creation plus a replace round-trip and two grid filter reloads is slow on a loaded dump env.
    test.setTimeout(300_000);

    const partCreatePage = new PartCreateView(page);
    const partIndexPage = new PartIndexView(page);
    const partReplacePage = new PartReplaceView(page);

    const originalSerial = UniqueId.generate("hqd367-orig");
    const replacementSerial = UniqueId.generate("hqd367-new");
    const warrantyTill = futureDateYmd(1);

    await partCreatePage.navigate();
    await partCreatePage.fillPartFields({
      partno: "EPYC 7402P",
      src_id: "TEST-DS-01",
      dst_id: "TEST-DS-02",
      serials: originalSerial,
      move_descr: "Warranty till test",
      price: 1,
      currency: "$",
      company_id: "Other",
      warranty_till: warrantyTill,
    });
    await partCreatePage.save();
    await partIndexPage.seePartWasCreated();

    await partIndexPage.navigateCommon();
    await partIndexPage.filterBySerial(originalSerial);
    await partIndexPage.selectPartsToReplace(1);

    await partReplacePage.fillReplaceForm([{ serialno: replacementSerial }]);
    await partReplacePage.save();

    await partIndexPage.confirmReplacementNotification();

    await partIndexPage.navigateCommon();
    await partIndexPage.filterBySerial(replacementSerial);
    await partIndexPage.seeReplacementInGrid();

    expect(await partIndexPage.getColumnValue("Warranty till")).toBe(warrantyTill);
  });
});
