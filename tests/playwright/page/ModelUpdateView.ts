import { expect, Page } from "@playwright/test";
import { Alert } from "@hipanel-core/shared/ui/components";

export type HardwareProp = {
  name: string;
  viewLabel: string;
  value: string;
};

export default class ModelUpdateView {
  constructor(private page: Page) {}

  async findFirstModelIdOfType(type: string): Promise<string> {
    await this.page.goto(`/stock/model/index?ModelSearch[type]=${type}`);
    const firstRow = this.page.locator("table tbody tr").first();
    await expect(firstRow).toBeVisible();
    const modelId = await firstRow.getAttribute("data-key");
    expect(modelId, `No ${type} models found in the index`).not.toBeNull();
    return modelId!;
  }

  async openUpdateForm(modelId: string): Promise<void> {
    await this.page.goto(`/stock/model/update?id=${modelId}`);
  }

  async openViewPage(modelId: string): Promise<void> {
    await this.page.goto(`/stock/model/view?id=${modelId}`);
    await this.page.waitForLoadState('networkidle');
  }

  async fillProps(props: HardwareProp[]): Promise<void> {
    await expect(this.page.locator(`input[id=model-0-props-${props[0].name}]`)).toBeVisible();
    for (const { name, value } of props) {
      await this.page.locator(`input[id=model-0-props-${name}]`).fill(value);
    }
  }

  async save(): Promise<void> {
    await this.page.locator("button:has-text(\"Save\")").click();
    await Alert.on(this.page).hasText("Model has been updated");
  }

  async assertViewProps(props: HardwareProp[]): Promise<void> {
    for (const { viewLabel, value } of props) {
      const td = this.page.locator("tr").filter({ hasText: viewLabel }).locator("td").last();
      await expect(td).toHaveText(value);
    }
  }

  async assertFormProps(props: HardwareProp[]): Promise<void> {
    for (const { name, value } of props) {
      await expect(this.page.locator(`input[id=model-0-props-${name}]`)).toHaveValue(value);
    }
  }
}
