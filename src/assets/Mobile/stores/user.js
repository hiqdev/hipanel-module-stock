import { defineStore } from "pinia";
import { ref } from "vue";
import api from "@/utils/api";
import useSessionStore from "@/stores/session";
import { isEmpty } from "lodash/lang";

const useUserStore = defineStore("user", () => {
  const id = ref(null);
  const email = ref(null);
  const username = ref(null);
  const personalId = ref(null);
  const session = useSessionStore();

  async function getUser() {
    const user = await api.getUser();
    id.value = user.id;
    email.value = user.email;
    username.value = user.username;
  }

  function resetPersonalId() {
    personalId.value = null;
  }

  function setPersonalId(value) {
    personalId.value = value;
  }

  function applySession(data) {
    if (!isEmpty(data.personalId)) {
      personalId.value = data.personalId;
    }
  }

  function collectSessionData() {
    return {
      personalId: personalId.value,
    };
  }

  return {
    id,
    email,
    username,
    personalId,
    getUser,
    resetPersonalId,
    setPersonalId,
    session,
    applySession,
    collectSessionData,
  };
});

export default useUserStore;
