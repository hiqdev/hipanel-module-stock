<script setup>
import { watch, nextTick } from "vue";
import PartView from "@/components/PartView.vue";
import OrderView from "@/components/OrderView.vue";
import ModelView from "@/components/ModelView.vue";
import useResolverStore from "@/stores/resolver";
import useStockStore from "@/stores/stock";
import useSelect from "@/use/select";

const resolver = useResolverStore();
const stock = useStockStore();
const { show } = useSelect();

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === true && ["part", "model", "order"].includes(resolver.resolvedName)) {
    show.value = true;
  }
});

function onClosed() {
  resolver.reset();
  nextTick(() => {
    const element = document.getElementById("any-code");
    if (element) {
      element.focus();
    }
  });
}

</script>

<style scoped>
.content {
  padding: 1em 1em 5em;
}
</style>

<template>
  <van-action-sheet v-model:show="show" :title="resolver.resolvedTitle" @closed="onClosed">
    <div class="content">
      <PartView v-if="resolver.resolvedName === 'part'"/>
      <OrderView v-else-if="resolver.resolvedName === 'order'"/>
      <ModelView v-else-if="resolver.resolvedName === 'model'"/>
    </div>
  </van-action-sheet>
</template>
