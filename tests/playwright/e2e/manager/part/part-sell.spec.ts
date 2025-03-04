import { test } from "@hipanel-core/fixtures";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";
import SellModal from "@hipanel-module-stock/page/SellModal";
import UniqueId from "@hipanel-core/helper/UniqueId";

test("Ensure I can sell parts @hipanel-module-stock @manager", async ({ page }) => {
    const partIndexView = new PartIndexView(page);
    const sellModal = new SellModal(page);

    const sellData = {
        contact_id: "Test Manager",
        currency: "eur",
        descr: UniqueId.generate(`test description`),
        type: "HW purchase",
        prices: [250, 300, 442],
        time: new Date(Date.now() - 86400000).toISOString().slice(0, 16)
    };

    await partIndexView.navigate();
    await partIndexView.filterBySerial("MG_TEST_PART");
    await partIndexView.selectRows(sellData.prices.length);
    await partIndexView.openSellModal();

    await sellModal.fillSellWindowFields(sellData);
    await sellModal.confirmSale();
    await sellModal.seePartsWereSold();
});
