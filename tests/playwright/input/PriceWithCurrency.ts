import InputWithCurrency from "@hipanel-core/input/InputWithCurrency";
import {Page} from "@playwright/test";

export default class PriceWithCurrency extends InputWithCurrency{
    static field(page: Page, formId: string, k: number): InputWithCurrency {
        return new PriceWithCurrency(page, `${formId}-${k}-price`);
    }
}