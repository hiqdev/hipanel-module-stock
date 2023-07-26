import { ref, computed } from "vue";
import { defineStore } from "pinia";
import useStockStore from "@/stores/stock";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useTaskStore from "@/stores/task";
import api from "@/utils/api";
import { find } from "lodash/collection";
import { toString } from "lodash/lang";

const useResolverStore = defineStore("resolver", () => {
  const stock = useStockStore();
  const ui = useUiStore();
  const user = useUserStore();
  const task = useTaskStore();

  const code = ref(null);
  const resolved = ref(null);
  const resolvedName = ref(null);
  const resolvedTitle = computed(() => {
    debugger
    switch (resolvedName.value) {
      case "part":
        return stock.partTitle(code.value);
        break;
      case "model":
        return stock.modelTitle(code.value);
        break;
      case "order":
        return stock.orderTitle(code.value);
        break;
    }
  });

  async function resolve() {
    if (code.value && code.value.length >= 3) {
      resolved.value = null;
      resolvedName.value = null;
      ui.startRequest();
      const data = resolveLocally() || await api.resolveCode(code.value, stock.location.name);
      resolvedName.value = data.resolveLike;
      ui.finishRequest();
      if (data.resolveLike === "destination") {
        stock.destination = data.result;
        resolved.value = true;
      } else if (data.resolveLike === "task") {
        task.url = data.result;
        resolved.value = true;
      } else if (data.resolveLike === "personal") {
        user.personalId = data.result;
        resolved.value = true;
      } else if (["part", "model", "order"].includes(data.resolveLike)) {
        stock.populate(code.value, data.result);
        resolved.value = true;
      } else {
        resolved.value = false;
      }
    }
  }

  function resolveLocally(data) {
    return stock.findLocally(code.value);
  }

  function reset() {
    code.value = null;
    resolvedName.value = null;
    resolved.value = null;
  }

  return {
    code, resolved, resolve, resolvedName, resolvedTitle, reset,
  };
});

export default useResolverStore;
