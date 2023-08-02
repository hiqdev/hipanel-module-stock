import { defineStore } from "pinia";
import { ref, computed } from "vue";

const useUiStore = defineStore("ui", (s) => {
  const pendingRequestsCount = ref(0);

  const theme = ref("light");
  const sound = ref("on");

  const isLoading = computed(() => pendingRequestsCount.value > 0);
  const isSoundOn = computed(() => sound.value === "on");

  function toggleTheme() {
    theme.value = theme.value === "light" ? "dark" : "light";
  }

  function toggleSound() {
    sound.value = sound.value === "on" ? "off" : "on";
  }

  function startRequest() {
    pendingRequestsCount.value++;
  }

  function finishRequest() {
    pendingRequestsCount.value--;
  }

  return {
    theme, sound, toggleTheme, isLoading, startRequest, finishRequest, isSoundOn,
  };
});

export default useUiStore;
