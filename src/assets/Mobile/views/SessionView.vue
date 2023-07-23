<template>
  <van-loading v-if="stockStore.uiStore.isLoading"/>
  <div v-else>
    <van-notice-bar>Select session or create new one to proceed.</van-notice-bar>
    <van-action-bar>
      <van-action-bar-button
          type="success"
          text="Create or select session"
          @click="show = true"
      />
      <van-action-sheet
          v-model:show="show"
          :actions="sessionStore.sessions"
          @select="onSelect"
          cancel-text="Create new session"
          close-on-click-action
          @cancel="onCancel"
      />
    </van-action-bar>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { onBeforeRouteLeave, useRouter } from "vue-router";
import { useStockStore } from "@/stores/stock";
import { useSessionStore } from "@/stores/session";
import { useUiStore } from "@/stores/ui";
import useSelect from "@/use/select";

const stockStore = useStockStore();
const sessionStore = useSessionStore();
const router = useRouter();
const uiStore = useUiStore();
const { show, onSelect } = useSelect((session) => {
  sessionStore.setSession(session);
  router.push({ name: "location" });
});

function onCancel() {
  stockStore.sessionStore.createSession();
  router.push({ name: "location" });
}

onBeforeRouteLeave((to, from) => {
  stockStore.getLocations();
});

</script>
