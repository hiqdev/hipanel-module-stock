import { test } from "@hipanel-core/fixtures";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";

const data = {
    filters: [
        {
            name: "move_descr_ilike",
            value: "test description"
        },
        {
            name: "model_types",
            value: "cpu"
        },
    ],
    replaceData: [
        { serialno: `test${Date.now()}` },
        { serialno: `test${Date.now()}` }
    ],
};

test.describe("Part Replacement", () => {
    test("Ensure parts can be replaced @hipanel-module-stock @manager", async ({ managerPage }) => {
        const partIndexView = new PartIndexView(managerPage);

        await partIndexView.navigate();
        await partIndexView.applyFilters(data.filters);
        await partIndexView.selectPartsToReplace(1, data.replaceData.length);
        // await partIndexView.fillReplaceForm(data.replaceData, data.rowNumbers);
        //await partIndexView.confirmReplacement();
    });
});