import { ref, computed, unref } from "vue";
import { defineStore } from "pinia";
import useUiStore from "@/stores/ui";
import api from "@/utils/api";
import remove from "lodash/remove";

const useStockStore = defineStore("stock", () => {
  const ui = useUiStore();

  const location = ref();
  const locations = ref([]);
  const model = ref(null);
  const order = ref(null);
  const models = ref([]);
  const parts = ref([]);
  const destination = ref(null);
  const isFinished = ref(null);
  const errorMessage = ref(null);

  const part = computed(() => parts.value.shift());
  const hasError = computed(() => errorMessage.value !== null);

  async function moveOrSendMessage() {
    ui.startRequest();
    errorMessage.value = null;
    let response;
    isFinished.value = false;
    if (destination.value !== null) {
      response = await api.move({
        parts: parts.value,
        destination: destination.value,
      });
    } else {
      response = await api.sendMessage();
    }
    ui.finishRequest();
    if (response.status === "success") {
      isFinished.value = response.status === "success";
    } else {
      errorMessage.value = response.errorMessage;
    }
  }

  async function getLocations() {
    ui.startRequest();
    locations.value = await api.getLocations();
    ui.finishRequest();
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
    location,
    model,
    part,
    parts,
    models,
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
    moveOrSendMessage,
    isFinished,
    hasError,
    errorMessage,
  };
});

export default useStockStore;
