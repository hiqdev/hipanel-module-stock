<script setup>
import { ref, watch } from "vue";
import PartView from "@/components/PartView.vue";
import OrderView from "@/components/OrderView.vue";
import ModelView from "@/components/ModelView.vue";
import useResolverStore from "@/stores/resolver";
import useStockStore from "@/stores/stock";
import useSelect from "@/use/select";
import { showNotify } from "vant";

const resolver = useResolverStore();
const stock = useStockStore();
const { show } = useSelect();

const part = ref(null);
const model = ref(null);
const order = ref(null);

watch(() => resolver.result, (newVal, prevVal) => {
  const name = resolver.resolvedName;
  if (["part", "model", "order"].includes(name)) {
    show.value = true;
  } else {
    resolver.reset();
    show.value = false;
  }
});

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === false) {
    show.value = false;
    resolver.reset();
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
      <PartView v-if="resolver.resolvedName === 'part'"/>
      <ModelView v-else-if="resolver.resolvedName === 'model'"/>
      <OrderView v-else-if="resolver.resolvedName === 'order'"/>
    </div>
  </van-popup>
</template>

<style scoped>
.content {
  padding: 1em;
}
</style>
