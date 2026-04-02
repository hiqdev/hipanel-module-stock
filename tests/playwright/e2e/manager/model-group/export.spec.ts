import { test } from "@hipanel-core/fixtures";
import { StockExport } from "@hipanel-module-stock/pages";

test("model-group export works correctly @hipanel-module-stock @manager", async ({ page }) => {
  test.setTimeout(180_000);
  const exportPage = new StockExport(page);

  await exportPage.startsWith("/stock/model-group/index");
});
