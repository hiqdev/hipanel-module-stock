<script setup>
import { ref, watch, nextTick } from "vue";
import PartView from "@/components/PartView.vue";
import OrderView from "@/components/OrderView.vue";
import ModelView from "@/components/ModelView.vue";
import useResolverStore from "@/stores/resolver";
import useStockStore from "@/stores/stock";
import useSelect from "@/use/select";

const resolver = useResolverStore();
const stock = useStockStore();
const { show } = useSelect();

const part = ref(null);
const model = ref(null);
const order = ref(null);

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === true && ["part", "model", "order"].includes(resolver.resolvedName)) {
    const data = stock.findLocally(resolver.code);
    if (data.resolveLike === "part") {
      part.value = data.result.parts[0];
    }
    if (data.resolveLike === "model") {
      model.value = data.result.models[0];
    }
    if (data.resolveLike === "order") {
      order.value = data.result.orders[0];
    }
    show.value = true;
  } else {
    resolver.reset();
    show.value = false;
  }
});

function onClosed() {
  resolver.reset();
}
</script>

<template>
  <van-popup v-model:show="show" @closed="onClosed" position="top" closeable :style="{height: '90%'}">
    <div class="content">
      <h4 style="text-align: center">{{ resolver.resolvedTitle }}</h4>
      <PartView v-if="resolver.resolvedName === 'part'" :part="part"/>
      <ModelView v-else-if="resolver.resolvedName === 'model'" :model="model"/>
      <OrderView v-else-if="resolver.resolvedName === 'order'" :order="order"/>
    </div>
  </van-popup>
</template>

<style scoped>
.content {
  padding: 1em;
}
</style>
