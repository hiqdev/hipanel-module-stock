<template>
  <van-loading v-if="uiStore.isLoading"/>
  <div v-else>
    <van-action-bar>
      <van-action-bar-button
          type="success"
          text="Select location"
          @click="show = true"
      />
      <van-action-sheet v-model:show="show" :actions="stockStore.locations" @select="onSelect"/>
    </van-action-bar>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter, onBeforeRouteUpdate } from "vue-router";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useUiStore from "@/stores/ui";
import useSelect from "@/use/select";

const stockStore = useStockStore();
const sessionStore = useSessionStore();
const uiStore = useUiStore();
const router = useRouter();
const { show, onSelect } = useSelect((location) => {
  stockStore.setLocation(location);
  router.push({ name: "any-code" });
});

function onBack() {
  stockStore.reset();
  sessionStore.reset();
  router.push({ name: "session" });
}

</script>
