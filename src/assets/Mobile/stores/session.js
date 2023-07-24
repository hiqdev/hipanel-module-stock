import { computed, ref, toRef } from "vue";
import { defineStore } from "pinia";
import api from "@/utils/api";
import useUiStore from "@/stores/ui";

const useSessionStore = defineStore("session", () => {
  const session = ref(null);
  const sessions = ref([]);
  const uiStore = useUiStore();

  async function getSessions() {
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

  async function deleteSession() {
    await api.deleteSession();
  }

  function reset() {
    session.value = null;
  }

  return {
    sessions,
    session,
    getSessions,
    createSession,
    setSession,
    deleteSession,
    reset,
  };
});

export default useSessionStore;
