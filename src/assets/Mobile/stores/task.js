import { defineStore } from "pinia";
import { ref, computed } from "vue";

const useTaskStore = defineStore("task", (s) => {
  const url = ref(null);

  function reset() {
    url.value = null;
  }

  return {
    url,
    reset,
  };
});

export default useTaskStore;
