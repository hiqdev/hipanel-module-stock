import { ref, computed } from "vue";
import { defineStore } from "pinia";
import useStockStore from "@/stores/stock";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useTaskStore from "@/stores/task";
import api from "@/utils/api";
import useBeeper from "@/use/beeper";
import { find } from "lodash/collection";
import { toString } from "lodash/lang";

const useResolverStore = defineStore("resolver", () => {
  const stock = useStockStore();
  const ui = useUiStore();
  const user = useUserStore();
  const task = useTaskStore();
  const {playSuccess, playError} = useBeeper();

  const code = ref(null);
  const resolved = ref(null);
  const resolvedName = ref(null);
  const resolvedTitle = computed(() => {
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

  function resolveTitle(value) {
    switch (resolvedName.value) {
      case "part":
        return stock.partTitle(value);
        break;
      case "model":
        return stock.modelTitle(value);
        break;
      case "order":
        return stock.orderTitle(value);
        break;
    }
  }

  async function resolve() {
    if (!code.value) {
      return;
    }

    resolved.value = null;
    resolvedName.value = null;
    ui.startRequest();
    let data = resolveLocally();
    if (data === null) {
      data = await api.resolveCode(code.value, stock.location.name);
    }
    resolvedName.value = data.resolveLike;
    ui.finishRequest();
    if (data.resolveLike === "destination") {
      stock.setDestination(data.result);
      resolved.value = true;
    } else if (data.resolveLike === "task") {
      task.setUrl(data.result);
      resolved.value = true;
    } else if (data.resolveLike === "personal") {
      user.setPersonalId(data.result);
      resolved.value = true;
    } else if (["part", "model", "order"].includes(data.resolveLike)) {
      stock.populate(code.value, data.result);
      resolved.value = true;
    } else {
      resolved.value = false;
    }

    if (resolved.value) {
      playSuccess();
    } else {
      playError();
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
    code, resolved, resolve, resolvedName, resolvedTitle, resolveTitle, reset,
  };
});

export default useResolverStore;
