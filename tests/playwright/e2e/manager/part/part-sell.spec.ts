import { test } from "@hipanel-core/fixtures";
import PartIndexView from "@hipanel-module-stock/page/PartIndexView";
import SellModal from "@hipanel-module-stock/page/SellModal";
import UniqueId from "@hipanel-core/helper/UniqueId";
import BillIndexView from "@hipanel-module-stock/page/BillIndexView";

test("Ensure I can sell parts @hipanel-module-stock @manager", async ({managerPage }) => {
    const partIndexView = new PartIndexView(managerPage);
    const sellModal = new SellModal(managerPage);
    const billIndexView = new BillIndexView(managerPage);

    const sellData = {
        contact_id: "Test Manager",
        currency: "eur",
        descr: UniqueId.generate(`test description`),
        type: "HW purchase",
        prices: [250, 300, 442],
        time: new Date(Date.now() - 86400000).toISOString().slice(0, 16).replace('T', ' ')
    };

    await partIndexView.navigate();
    await partIndexView.filterBySerial("MG_TEST_PART");
    await partIndexView.selectRows(sellData.prices.length);
    await partIndexView.openSellModal();

    await sellModal.fillSellWindowFields(sellData);
    await sellModal.confirmSale();
    await sellModal.seePartsWereSold();

    await billIndexView.ensureSellingBillWasCreated(sellData);
});
