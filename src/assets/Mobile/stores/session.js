import { computed, ref, toRef } from "vue";
import moment from "moment";
import { defineStore } from "pinia";
import api from "@/utils/api";
import { find, forEach } from "lodash/collection";
import useUiStore from "@/stores/ui";
import useTaskStore from "@/stores/task";
import { isEmpty, toInteger } from "lodash/lang";

const useSessionStore = defineStore("session", () => {
  const ui = useUiStore();
  const task = useTaskStore();

  const session = ref(null);
  const sessions = ref([]);
  const name = computed(() => getName(session.value));
  const sessionList = computed(() => {
    const list = [];
    forEach(sessions.value, (item) => {
      list.push({
        id: item.id,
        name: getName(item),
        subname: moment.unix(toInteger(item.id)).fromNow(),
      });
    });

    return list;
  });

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
    session.value = find(sessions.value, s => s.id === value.id);
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

  function getName(item) {
    const parts = [];
    if (!isEmpty(item.location)) {
      parts.push(item.location.name);
    }
    if (!isEmpty(item.taskUrl)) {
      parts.push(task.toName(item.taskUrl));
    }

    return !isEmpty(parts) ? parts.join(" ") : "--";
  }

  return {
    sessionList,
    session,
    name,
    getSessions,
    createSession,
    setSession,
    deleteSession,
    reset,
  };
});

export default useSessionStore;
