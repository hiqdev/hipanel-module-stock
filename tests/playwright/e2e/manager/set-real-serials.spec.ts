import { test } from "@hipanel-core/tests/fixtures";
import { expect } from "@playwright/test";

// TODO: need to implement

test.beforeEach(async ({ managerPage }) => {
  await managerPage.goto("/stock/part/index?PartSearch[partno]=SC815TQ-600WB&PartSearch[dst_name_in]=TEST-DS-02");
  await expect(managerPage).toHaveTitle("Parts");
});

test("Test I can change serial to real @hipanel-module-stock @manager", async ({ managerPage }) => {
  const asdf = 1;
  expect(1).toBe(1);
});
