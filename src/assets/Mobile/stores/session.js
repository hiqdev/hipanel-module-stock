import { computed, ref, toRef } from "vue";
import { defineStore } from "pinia";
import api from "@/utils/api";
import { useUiStore } from "@/stores/ui";

export const useSessionStore = defineStore("session", () => {
  const session = ref(null);
  const sessions = ref([]);
  const uiStore = useUiStore();

  async function init() {
    uiStore.startRequest();
    sessions.value = await api.getSessions();
    uiStore.finishRequest();
  };

  async function createSession() {
    uiStore.startRequest();
    session.value = await api.createSession();
    uiStore.finishRequest();
  }

  function setSession(value) {
    session.value = value;
  }

  function deleteSession(sessionId) {
    alert("delete session");
  }

  function reset() {
    session.value = null;
  }

  init();

  return {
    sessions,
    session,
    createSession,
    setSession,
    reset,
  };
});
