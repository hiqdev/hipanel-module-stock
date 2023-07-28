<script setup>
import { ref } from "vue";
import { onBeforeRouteLeave, useRouter } from "vue-router";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useTaskStore from "@/stores/task";
import useSelect from "@/use/select";
import { isEmpty } from "lodash/lang";

const stock = useStockStore();
const session = useSessionStore();
const router = useRouter();
const ui = useUiStore();
const user = useUserStore();
const task = useTaskStore();

const { show, onSelect } = useSelect((item) => {
  session.setSession(item);
  stock.applySession(session.session);
  user.applySession(session.session);
  task.applySession(session.session);
  if (isEmpty(stock.location)) {
    router.push({ name: "location" });
  } else {
    router.push({ name: "any-code" });
  }
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
        :actions="session.sessionList"
        @select="onSelect"
        @cancel="onCancel"
        cancel-text="Create new session"
        close-on-click-action
    />
  </van-action-bar>
</template>
