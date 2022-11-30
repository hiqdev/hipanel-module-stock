import { test } from "@hipanel-core/fixtures";
import {expect, Page} from "@playwright/test";
import Index from "@hipanel-core/page/Index";

test.describe("Ensure I can change serial to real @hipanel-module-stock", () => {

  let indexPage: Index

  test.beforeEach(async ({ managerPage }) => {
    indexPage = new Index(managerPage);
    await managerPage.goto('/stock/part/index?PartSearch[partno]=SC815TQ-600WB&PartSearch[dst_name_in]=TEST-DS-02');
    await expect(managerPage).toHaveTitle('Parts');
    await indexPage.hasRowsOnTable(1);
  });

  test("Set serial as part ID @manager", async ({ managerPage }) => {
    await indexPage.chooseNumberRowOnTable(1);
    await indexPage.clickDropdownBulkButton('Bulk actions', 'Set serial');
    await setSerialAsPartId(managerPage);
  });

  test("Set real serial for one part @manager", async ({ managerPage }) => {
    await indexPage.chooseNumberRowOnTable(1);
    await indexPage.clickDropdownBulkButton('Bulk actions', 'Set real serials');
    await tryToSetTwoRealSerialsForOnePart('test_real_serials_1', managerPage);
    await setRealSerial('test_real_serials_1', managerPage);
  });
});

async function setSerialAsPartId(page: Page) {
  const id = await page.locator('#set-serial-form input[id*=id]').inputValue();
  await page.locator('#set-serial-form div[class*=serial] input').fill(id);
  await page.locator('text=Submit').click();
  await expect(page.locator(`td:has-text("${id}")`)).toHaveCount(1);
}

async function tryToSetTwoRealSerialsForOnePart(realSerial: string, page: Page) {
  await page.locator('textarea[id*=serials]').fill(`${realSerial}, ${realSerial}`);
  await page.locator('text=Save').click();
  await expect(page.locator('text=Serial numbers should have been put in the same amount as the selected parts')).toHaveCount(1);
}

async function setRealSerial(realSerial: string, page: Page) {
  await page.locator('textarea[id*=serials]').fill(`${realSerial}`);
  await page.locator('text=Save').click();
  await expect(page.locator(`td:has-text("${realSerial}")`)).toHaveCount(1);
}
