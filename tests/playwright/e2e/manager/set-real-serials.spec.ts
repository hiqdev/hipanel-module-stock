import { test } from "@hipanel-core/fixtures";
import { expect } from "@playwright/test";
import PartsPage from "@hipanel-module-stock/e2e/pages/PartsPage";
import Index from "@hipanel-core/page/Index";

test("Ensure I can change serial to real @hipanel-module-stock @manager", async ({ managerPage }) => {

  const indexPage = new Index(managerPage);
  const partsPage = new PartsPage(managerPage);

  await partsPage.gotoParts({partno: 'SC815TQ-600WB', dst_name_in: 'TEST-DS-02'});

  await indexPage.hasTitle('Parts');
  await indexPage.hasRowsOnTable(1);

  await indexPage.chooseNumberRowOnTable(1);
  await indexPage.clickDropdownBulkButton('Bulk actions', 'Set serial');
  await partsPage.setSerialAsPartId();

  await indexPage.chooseNumberRowOnTable(1)
  await indexPage.clickDropdownBulkButton('Bulk actions', 'Set real serials');
  await partsPage.tryToSetTwoRealSerialsForOnePart('test_real_serials_1');
  await partsPage.setRealSerial('test_real_serials_1');

});
