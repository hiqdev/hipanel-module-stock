import { test } from "@hipanel-core/fixtures";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";
import PartReplaceView from "@hipanel-module-stock/page/PartReplaceView";
import UniqueId from "@hipanel-core/helper/UniqueId";

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
        { serialno: UniqueId.generate(`test`) },
        { serialno: UniqueId.generate(`test`) }
    ],
};

test.describe("Part Replacement", () => {
    test("Ensure parts can be replaced @hipanel-module-stock @manager", async ({ managerPage }) => {
        const partIndexView = new PartIndexView(managerPage);
        const partReplaceView = new PartReplaceView(managerPage);

        await partIndexView.navigate();
        await partIndexView.applyFilters(data.filters);
        await partIndexView.selectPartsToReplace(1, data.replaceData.length);

        await partReplaceView.fillReplaceForm(data.replaceData);
        await partReplaceView.save();

        await partIndexView.confirmReplacement();
    });
});