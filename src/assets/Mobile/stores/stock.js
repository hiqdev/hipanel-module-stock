import { ref, computed, unref } from "vue";
import { defineStore } from "pinia";
import { find, groupBy, forEach, filter } from "lodash/collection";
import { remove } from "lodash/array";
import { toString, isEmpty, toInteger } from "lodash/lang";

import api from "@/utils/api";
import useBeeper from "@/use/beeper";
import useUiStore from "@/stores/ui";
import useTaskStore from "@/stores/task";
import useUserStore from "@/stores/user";
import useSessionStore from "@/stores/session";

const useStockStore = defineStore("stock", () => {
  const ui = useUiStore();
  const task = useTaskStore();
  const user = useUserStore();
  const session = useSessionStore();
  const { playExists } = useBeeper();

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
  const serialDuplicate = ref(null);

  const inModelCount = (computed(() => {
    return (modelId) => {
      const serialsCount = filter(serials.value, part => toString(part.model_id) === toString(modelId)).length;
      const allInModelsCount = filter(parts.value, part => toString(part.model_id) === toString(modelId)).length;

      return `${serialsCount} / ${allInModelsCount}`;
    };
  }));
  const partTitle = computed(() => (serial) => {
    const part = find(parts.value, part => part.serial === serial);

    return part ? `${part.model_type_label} ${part.partno} #${serial}` : "Part";
  });
  const modelTitle = computed(() => (partno) => {
    const model = find(models.value, model => model.partno === partno);

    return model ? `${model.type_label} ${model.model}` : "Model";
  });
  const orderTitle = computed(() => (name) => {
    return name;
  });
  const model = computed(() => models.value.shift());
  const order = computed(() => orders.value.shift());
  const part = computed(() => parts.value.shift());
  const hasError = computed(() => errorMessage.value !== null);

  async function complete() {
    ui.startRequest();
    errorMessage.value = null;
    isFinished.value = false;
    const data = {
      parts: serials.value,
      comment: comment.value,
      taskUrl: task.url,
      personal: user.personalId,
    };
    if (destination.value !== null) {
      data.destination = destination.value;
    }
    const response = await api.complete(data);
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
    const exists = find(serials.value, p => p.serial === part.serial);
    if (!exists) {
      serials.value.unshift(part);
    } else {
      if (ui.isSoundOn) {
        playExists();
      }
      serialDuplicate.value = part;
    }
  }

  async function removeDuplicate() {
    await new Promise((resolve) => {
      const duplicate = Object.assign({}, serialDuplicate.value);
      removeSerial(duplicate);
      serialDuplicate.value = null;
    });
  }

  function removeSerial(part) {
    remove(serials.value, (entry) => entry.serial === part.serial);
  }

  function setLocation(value) {
    location.value = value;
  }

  function reset() {
    serials.value = [];
    location.value = null;
    destination.value = null;
  }

  function setDestination(value) {
    destination.value = value;
  }

  function resetDestination() {
    destination.value = null;
  }

  function setLocation(value) {
    location.value = value;
  }

  function resetLocation() {
    location.value = null;
  }

  function resetSerials() {
    serials.value = [];
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
      if (model) {
        model.parts = items;
        result.push(model);
      }
    });

    return result;
  }

  function modelWithParts(modelId) {
    const model = find(models.value, model => toString(model.id) === toString(modelId));
    const entry = Object.assign({}, model);
    const modelParts = filter(parts.value, part => toString(part.model_id) === toString(modelId));
    entry.parts = modelParts;

    return entry;
  }

  function orderWithParts(orderId) {
    const order = find(orders.value, order => toString(order.id) === toString(orderId));
    const entry = Object.assign({}, order);
    const orderParts = filter(parts.value, part => toString(part.order_id) === toString(orderId));
    entry.parts = orderParts;

    return entry;
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
      const inStockCount = Object.values(model.counters[location.value.name]).reduce((a, b) => toInteger(a) + toInteger(b), 0);
      const loadedCount = filter(parts.value, p => p.model_id === model.id).length;
      if (inStockCount > loadedCount) {
        // load from server
        return null;
      }
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

  function applySession(data) {
    if (!isEmpty(data.models)) {
      models.value = data.models;
    }
    if (!isEmpty(data.parts)) {
      parts.value = data.parts;
    }
    if (!isEmpty(data.serials)) {
      serials.value = data.serials;
    }
    if (!isEmpty(data.location)) {
      location.value = data.location;
    }
    if (!isEmpty(data.destination)) {
      destination.value = data.destination;
    }
  }

  function collectSessionData() {
    return {
      location: location.value,
      serials: serials.value, models: models.value,
      parts: parts.value,
      destination: destination.value,
    };
  }

  return {
    location,
    serials,
    destination,
    locations,
    getLocations,
    setLocation,
    reset,
    resetLocation,
    resetDestination,
    resetSerials,
    populate,
    removeSerial,
    complete,
    isFinished,
    hasError,
    errorMessage,
    modelsWithSerials,
    comment,
    findLocally,
    inModelCount,
    partTitle,
    modelTitle,
    orderTitle,
    modelWithParts,
    orderWithParts,
    session,
    applySession,
    setDestination,
    collectSessionData,
    serialDuplicate,
    removeDuplicate,
  };
});

export default useStockStore;
