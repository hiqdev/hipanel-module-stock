import { test } from "@hipanel-core/fixtures";
import StockExport from "@hipanel-module-stock/model/StockExport";

test("part export works correctly @hipanel-module-stock @manager", async ({ page }) => {
  test.setTimeout(180_000);
  const exportPage = new StockExport(page);

  await exportPage.startWith("/stock/part/index");
});
