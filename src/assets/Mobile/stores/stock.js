import { ref, computed } from "vue";
import { defineStore } from "pinia";
import { useSessionStore } from "@/stores/session";
import { useUiStore } from "@/stores/ui";
import api from "@/utils/api";
import remove from "lodash/remove";

export const useStockStore = defineStore("stock", () => {
  const sessionStore = useSessionStore();
  const uiStore = useUiStore();

  const location = ref();
  const locations = ref([]);
  const model = ref(null);
  const order = ref(null);
  const parts = ref([]);
  const destination = ref(null);

  const part = computed(() => parts.value.shift());

  async function getLocations() {
    uiStore.startRequest();
    locations.value = await api.getLocations();
    uiStore.finishRequest();
  }

  function addPart(part) {
    remove(parts.value, (entry) => entry.serial === part.serial);
    if (part.device_location === location.value.name) {
      parts.value.unshift(part);
    }
  }

  function removePart(part) {
    remove(parts.value, (entry) => entry.serial === part.serial);
  }

  function setLocation(value) {
    location.value = value;
  }

  function reset() {
    parts.value = [];
    model.value = null;
    order.value = null;
  }

  function resetDestination() {
    destination.value = null;
  }

  function resetWithLocation() {
    location.value = null;
    parts.value = [];
    model.value = null;
    order.value = null;
  }

  return {
    sessionStore,
    uiStore,
    location,
    model,
    part,
    parts,
    order,
    destination,
    locations,
    getLocations,
    setLocation,
    reset,
    resetWithLocation,
    resetDestination,
    addPart,
    removePart,
  };
});
