import { defineStore } from "pinia";
import { ref, computed } from "vue";
import useSessionStore from "@/stores/session";
import split from "lodash/split";

const useTaskStore = defineStore("task", (s) => {
  const session = useSessionStore();
  const url = ref(null);
  const name = computed(() => {
    return split(url.value, "/").slice(-2).join("/");
  });

  function reset() {
    url.value = null;
  }

  return {
    url,
    name,
    reset,
    session,
  };
});

export default useTaskStore;
