import { defineStore } from "pinia";
import { ref, computed } from "vue";
import useSessionStore from "@/stores/session";

const useTaskStore = defineStore("task", (s) => {
  const session = useSessionStore();
  const url = ref(null);

  function reset() {
    url.value = null;
  }

  return {
    url,
    reset,
    session,
  };
});

export default useTaskStore;
