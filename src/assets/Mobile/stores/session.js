import { computed, ref, toRef } from "vue";
import { defineStore } from "pinia";
import api from "@/utils/api";
import useUiStore from "@/stores/ui";

const useSessionStore = defineStore("session", () => {
  const session = ref(null);
  const sessions = ref([]);
  const ui = useUiStore();

  async function getSessions() {
    ui.startRequest();
    sessions.value = await api.getSessions();
    ui.finishRequest();
  };

  async function createSession() {
    ui.startRequest();
    session.value = await api.createSession();
    ui.finishRequest();
  }

  function setSession(value) {
    session.value = value;
  }

  async function deleteSession() {
    ui.startRequest();
    await api.deleteSession(session.value.id);
    ui.finishRequest();
    reset();
  }

  function reset() {
    session.value = null;
    sessions.value = [];
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
