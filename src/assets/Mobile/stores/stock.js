import { ref, computed, unref } from "vue";
import { defineStore } from "pinia";
import { find, groupBy, forEach, filter } from "lodash/collection";
import { remove } from "lodash/array";
import { toString, isEmpty } from "lodash/lang";

import api from "@/utils/api";
import useUiStore from "@/stores/ui";

const useStockStore = defineStore("stock", () => {
  const ui = useUiStore();

  const location = ref();
  const locations = ref([]);
  const serials = ref([]);
  const models = ref([]);
  const parts = ref([]);
  const orders = ref([]);
  const destination = ref(null);
  const comment = ref(null);
  const isFinished = ref(null);
  const errorMessage = ref(null);

  const inModelCount = (computed(() => {
    return (modelId) => {
      const serialsCount = filter(serials.value, part => toString(part.model_id) === toString(modelId)).length;
      const allInModelsCount = filter(parts.value, part => toString(part.model_id) === toString(modelId)).length;

      return `${serialsCount} / ${allInModelsCount}`;
    };
  }));
  const model = computed(() => models.value.shift());
  const order = computed(() => orders.value.shift());
  const part = computed(() => parts.value.shift());
  const hasError = computed(() => errorMessage.value !== null);

  async function moveOrSendMessage() {
    ui.startRequest();
    errorMessage.value = null;
    let response;
    isFinished.value = false;
    if (destination.value !== null) {
      response = await api.move({
        parts: serials.value,
        destination: destination.value,
      });
    } else {
      response = await api.sendMessage({
        parts: serials.value,
      });
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

  function addSerial(part) {
    remove(serials.value, (entry) => entry.serial === part.serial);
    serials.value.unshift(part);
  }

  function removeSerial(part) {
    remove(serials.value, (entry) => entry.serial === part.serial);
  }

  function setLocation(value) {
    location.value = value;
  }

  function reset() {
    parts.value = [];
  }

  function resetDestination() {
    destination.value = null;
  }

  function resetWithLocation() {
    location.value = null;
    reset();
  }

  function populate(code, data) {
    const serial = find(data.parts, (part) => code === part.serial);
    if (serial) {
      addSerial(serial);
    }
    if (!isEmpty(data.parts)) {
      forEach(data.parts, part => {
        const found = find(parts.value, p => toString(p.id) === toString(part.id));
        if (!found) {
          parts.value.push(part);
        }
      });
    }
    if (!isEmpty(data.models)) {
      forEach(data.models, model => {
        const found = find(models.value, m => toString(m.id) === toString(model.id));
        if (!found) {
          models.value.push(model);
        }
      });
    }
    if (!isEmpty(data.orders)) {
      forEach(data.orders, order => {
        const found = find(orders.value, o => toString(o.id) === toString(order.id));
        if (!found) {
          orders.value.push(order);
        }
      });
    }
  }

  function modelsWithSerials() {
    const result = [];
    const group = groupBy(serials.value, "model_id");
    if (isEmpty(group)) {
      return [];
    }
    forEach(group, (items, modelId) => {
      const model = find(models.value, model => toString(model.id) === toString(modelId));
      model.parts = items;
      result.push(model);
    });

    return result;
  }

  function findLocally(code) {
    const data = {
      resolveLike: null,
      result: {
        parts: [],
        models: [],
        orders: [],
      },
    };
    const part = find(parts.value, part => toString(part.serial) === code);
    if (part) {
      data.resolveLike = "part";
      data.result.parts = [part];

      return data;
    }
    const model = find(models.value, model => toString(model.partno) === code);
    if (model) {
      data.resolveLike = "model";
      data.result.models = [model];

      return data;
    }
    const order = find(orders.value, order => toString(order.name) === code);
    if (order) {
      data.resolveLike = "order";
      data.result.orders.push(order);

      return data;
    }

    return null;
  }

  return {
    location,
    model,
    part,
    order,
    serials,
    destination,
    locations,
    getLocations,
    setLocation,
    reset,
    resetWithLocation,
    resetDestination,
    populate,
    removeSerial,
    moveOrSendMessage,
    isFinished,
    hasError,
    errorMessage,
    modelsWithSerials,
    comment,
    findLocally,
    inModelCount,
  };
});

export default useStockStore;
