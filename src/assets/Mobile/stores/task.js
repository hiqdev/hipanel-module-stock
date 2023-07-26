import { defineStore } from "pinia";
import { ref, computed } from "vue";
import useUiStore from "@/stores/ui";

const useTaskStore = defineStore("task", (s) => {
  const id = ref("123");

  const uiStore = useUiStore();

  function reset() {
    id.value = null;
  }

  return {
    id,
    reset,
  };
});

export default useTaskStore;
