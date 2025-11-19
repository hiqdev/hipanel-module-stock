import { test } from "@hipanel-core/fixtures";
import StockExport from "@hipanel-module-stock/model/StockExport";

test("model-group export works correctly @hipanel-module-stock @manager", async ({ page }) => {
  const exportPage = new StockExport(page);

  await exportPage.startWith("/stock/model-group/index");
});
