import { test } from "@hipanel-core/fixtures";
import {expect} from "@playwright/test";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";


test("Ensure I can look parts buyer on selling representation after filtering @hipanel-module-stock @manager", async ({page }) => {
    const partIndexView = new PartIndexView(page);

    await partIndexView.navigateSelling();
    await partIndexView.applyFiltersByBuyer("solex");

    const td =  page.locator(
        'table.table.table-striped.table-bordered.table-condensed > tbody > tr:first-child > td:nth-child(2)'
    )

    await expect(td).toContainText("solex");
});
