import { test } from "@hipanel-core/fixtures";
import ModelUpdateView, { HardwareProp } from "@hipanel-module-stock/page/ModelUpdateView";

const modelTypeProps: { type: string; props: HardwareProp[] }[] = [
  {
    type: "motherboard",
    props: [
      { name: "max_ram_size", viewLabel: "Max RAM",     value: "512" },
      { name: "ram_slots",    viewLabel: "RAM slots",   value: "8"   },
      { name: "cpu_sockets",  viewLabel: "CPU sockets", value: "2"   },
    ],
  },
  {
    type: "server",
    props: [
      { name: "units_qty",  viewLabel: "Units Qty",   value: "2" },
      { name: "25_hdd_qty", viewLabel: "25 Hdd Qty",  value: "4" },
      { name: "35_hdd_qty", viewLabel: "35 Hdd Qty",  value: "8" },
    ],
  },
  {
    type: "chassis",
    props: [
      { name: "units_qty",  viewLabel: "Units Qty",   value: "2" },
      { name: "25_hdd_qty", viewLabel: "25 Hdd Qty",  value: "4" },
      { name: "35_hdd_qty", viewLabel: "35 Hdd Qty",  value: "8" },
    ],
  },
  {
    type: "hdd",
    props: [
      { name: "formfactor", viewLabel: "Formfactor", value: "3.5" },
    ],
  },
  {
    type: "ssd",
    props: [
      { name: "formfactor", viewLabel: "Formfactor", value: "2.5" },
    ],
  },
  {
    type: "ram",
    props: [
      { name: "size", viewLabel: "Size", value: "32" },
    ],
  },
];

test.describe("Model Hardware Properties", () => {
  for (const { type, props } of modelTypeProps) {
    test(`${type} hardware properties are saved and displayed after update @hipanel-module-stock @manager`, async ({ managerPage }) => {
      const modelView = new ModelUpdateView(managerPage);
      const modelId = await modelView.findFirstModelIdOfType(type);

      await modelView.openUpdateForm(modelId);
      await modelView.fillProps(props);
      await modelView.save();

      await modelView.openViewPage(modelId);
      await modelView.assertViewProps(props);

      await modelView.openUpdateForm(modelId);
      await modelView.assertFormProps(props);
    });
  }
});
