import { test } from "@hipanel-core/fixtures";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";
import PartReplaceView from "@hipanel-module-stock/page/PartReplaceView";
import { UniqueId } from "@hipanel-core/shared/lib";

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
    { serialno: UniqueId.generate("test") },
    { serialno: UniqueId.generate("test") },
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
});
