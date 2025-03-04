import { test } from "@hipanel-core/fixtures";
import {expect} from "@playwright/test";
import PartCreateView from "@hipanel-module-stock/page/PartCreateView";

const partData = {
    partno: 'CHASSIS EPYC 7402P',
    src_id: 'TEST-DS-01',
    dst_id: 'TEST-DS-02',
    serials: `MG_TEST_PART${Date.now()}`,
    move_descr: 'MG TEST MOVE',
    price: 200,
    currency: '$',
    company_id: 'Other',
};

test.describe('Part Management', () => {
    test('Ensure part management buttons work @hipanel-module-stock @manager', async ({ managerPage }) => {
        const partView = new PartCreateView(managerPage);
        await partView.navigate();

        let n = await managerPage.locator('div.item').count();
        expect(n).toBe(1);

        await partView.addPart();
        expect(await managerPage.locator('div.item').count()).toBe(++n);

        await partView.addPart();
        expect(await managerPage.locator('div.item').count()).toBe(++n);

        await partView.copyPart();
        expect(await managerPage.locator('div.item').count()).toBe(++n);

        await partView.removePart();
        expect(await managerPage.locator('div.item').count()).toBe(--n);

        await partView.removePart();
        expect(await managerPage.locator('div.item').count()).toBe(--n);

        await partView.removePart();
        expect(await managerPage.locator('div.item').count()).toBe(--n);
    });

    test('Ensure part cannot be created without data @hipanel-module-stock @manager', async ({ managerPage }) => {
        const partView = new PartCreateView(managerPage);
        await partView.navigate();
        await partView.save();

        const errorMessages = [
            'Part No. cannot be blank.',
            'Source cannot be blank.',
            'Destination cannot be blank.',
            'Serials cannot be blank.',
            'Move description cannot be blank.',
            'Purchase price cannot be blank.',
            'Currency cannot be blank.',
        ];

        for (const message of errorMessages) {
            await expect(managerPage.locator(`text=${message}`)).toBeVisible();
        }
    });

    test('Ensure a part can be created @hipanel-module-stock @manager', async ({ managerPage }) => {
        const partView = new PartCreateView(managerPage);
        await partView.navigate();
        await partView.fillPartFields(partData);
        await partView.save();
        await expect(managerPage.locator('text=Part has been created')).toBeVisible();
    });

    test('Ensure multiple parts can be created @hipanel-module-stock @manager', async ({ managerPage }) => {
        const partView = new PartCreateView(managerPage);
        await partView.navigate();
        await partView.fillPartFields(partData);
        await partView.addPart();
        await partView.save();
        await expect(managerPage.locator('text=Part has been created')).toBeVisible();
    });

    test('Ensure a part can be created and then deleted @hipanel-module-stock @manager', async ({ managerPage }) => {
        const partView = new PartCreateView(managerPage);
        await partView.navigate();
        await partView.fillPartFields(partData);
        await partView.save();
        await expect(managerPage.locator('text=Part has been created')).toBeVisible();

        await managerPage.click('text=Delete');
        managerPage.on('dialog', async dialog => await dialog.accept());
        await expect(managerPage.locator('text=Part has been deleted')).toBeVisible();
    });
});
