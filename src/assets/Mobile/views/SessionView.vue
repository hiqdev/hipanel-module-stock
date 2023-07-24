<script setup>
import { ref } from "vue";
import { onBeforeRouteLeave, useRouter } from "vue-router";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useUiStore from "@/stores/ui";
import useSelect from "@/use/select";

const stock = useStockStore();
const session = useSessionStore();
const router = useRouter();
const ui = useUiStore();

const { show, onSelect } = useSelect((session) => {
  session.setSession(session);
  router.push({ name: "location" });
});

function onCancel() {
  session.createSession();
  router.push({ name: "location" });
}

session.getSessions();

onBeforeRouteLeave((to, from) => {
  stock.getLocations();
});
</script>

<template>
  <van-notice-bar>Select session or create new one to proceed.</van-notice-bar>
  <van-action-bar>
    <van-loading v-if="ui.isLoading" vertical/>
    <van-action-bar-button
        v-else
        type="success"
        text="Create or select session"
        @click="show = true"
    />
    <van-action-sheet
        v-model:show="show"
        :actions="session.sessions"
        @select="onSelect"
        @cancel="onCancel"
        cancel-text="Create new session"
        close-on-click-action
    />
  </van-action-bar>
</template>
