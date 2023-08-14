import { ref, computed } from "vue";
import { defineStore } from "pinia";
import api from "@/utils/api";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useStockStore from "@/stores/stock";
import useTaskStore from "@/stores/task";
import useSessionStore from "@/stores/session";

const useCompleteStore = defineStore("complete", () => {
  const user = useUserStore();
  const stock = useStockStore();
  const task = useTaskStore();
  const ui = useUiStore();
  const session = useSessionStore();

  const isCompleted = ref(false);

  const canBeCompleted = computed(() => task.url !== null && user.personalId !== null && stock.serials.length > 0);

  async function complete() {
    await stock.complete();
    if (!stock.hasError) {
      session.deleteSession(session.id);
    }
  }

  function setComplete() {
    isCompleted.value = true;
  }

  return {
    canBeCompleted, isCompleted, complete, setComplete,
  };
});

export default useCompleteStore;
