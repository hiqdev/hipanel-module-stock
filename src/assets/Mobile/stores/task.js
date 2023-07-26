import { defineStore } from "pinia";
import { ref, computed } from "vue";
import useUiStore from "@/stores/ui";

const useTaskStore = defineStore("task", (s) => {
  const url = ref("https://hm4.advancedhosters.com/en/hm/thread/994140");

  const uiStore = useUiStore();

  function reset() {
    url.value = null;
  }

  return {
    url,
    reset,
  };
});

export default useTaskStore;
