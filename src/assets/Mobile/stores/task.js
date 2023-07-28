import { defineStore } from "pinia";
import { ref, computed } from "vue";
import useSessionStore from "@/stores/session";
import split from "lodash/split";
import isEmpty from "lodash/isEmpty";

const useTaskStore = defineStore("task", (s) => {
  const session = useSessionStore();
  const url = ref(null);
  const name = computed(() => {
    return toName(url.value);
  });

  function reset() {
    url.value = null;
  }

  function setUrl(value) {
    url.value = value;
  }

  function toName(value) {
    return split(value, "/").slice(-2).join("/");
  }

  function applySession(data) {
    if (!isEmpty(data.taskUrl)) {
      url.value = data.taskUrl;
    }
  }

  function collectSessionData() {
    return {
      taskUrl: url.value,
    };
  }

  return {
    url,
    name,
    toName,
    reset,
    setUrl,
    session,
    applySession,
    collectSessionData,
  };
});

export default useTaskStore;
