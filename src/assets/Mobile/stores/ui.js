import { defineStore } from "pinia";
import { ref, computed } from "vue";

const useUiStore = defineStore("ui", (s) => {
  const pendingRequestsCount = ref(0);
  const isLoading = computed(() => pendingRequestsCount.value > 0);
  const theme = ref("light");

  function toggleTheme() {
    theme.value = theme.value === "light" ? "dark" : "light";
  }

  function startRequest() {
    pendingRequestsCount.value++;
  }

  function finishRequest() {
    pendingRequestsCount.value--;
  }

  return {
    theme, toggleTheme, isLoading, startRequest, finishRequest,
  };
});

export default useUiStore;
