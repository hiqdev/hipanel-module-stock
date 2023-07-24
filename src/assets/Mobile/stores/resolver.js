import { ref, computed } from "vue";
import { defineStore } from "pinia";
import useStockStore from "@/stores/stock";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useTaskStore from "@/stores/task";
import api from "@/utils/api";

const useResolverStore = defineStore("resolver", () => {
  const code = ref(null);
  const resolved = ref(null);
  const resolvedName = ref(null);
  const resolvedTitle = computed(() => {
    switch (resolvedName.value) {
      case "part":
        return "Part title";
        break;
      case "model":
        return "Model title";
        break;
      case "order":
        return "Order title";
        break;
    }
  });

  const stock = useStockStore();
  const ui = useUiStore();
  const user = useUserStore();
  const task = useTaskStore();

  async function resolve() {
    if (code.value && code.value.length >= 3) {
      resolved.value = null;
      ui.startRequest();
      const data = await api.resolveCode(code.value, stock.location.name);
      ui.finishRequest();
      switch (data.resolveLike) {
        case "part":
          stock.addPart(data.result);
          resolvedName.value = "part";
          resolved.value = true;
          break;
        case "model":
          stock.model = data.result;
          resolvedName.value = "model";
          resolved.value = true;
          break;
        case "order":
          stock.order = data.result;
          resolvedName.value = "order";
          resolved.value = true;
          break;
        case "destination":
          stock.destination = data.result;
          resolvedName.value = "destination";
          resolved.value = true;
          break;
        case "task":
          task.id = data.result;
          resolvedName.value = "task";
          resolved.value = true;
          break;
        case "personal":
          user.personalId = data.result;
          resolvedName.value = "personal";
          resolved.value = true;
          break;
        default:
          resolved.value = false;
      }
    }
  }

  return {
    code, resolved, resolve, resolvedName, resolvedTitle,
  };
});

export default useResolverStore;
