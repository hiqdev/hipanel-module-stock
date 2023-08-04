<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
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
const { show, onSelect } = useSelect(async (l) => {
  await session.createSession();
  await stock.setLocation(l);
  router.push({ name: "any-code" });
});

async function onSelectSession(item) {
  await new Promise(async r => {
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
};

session.getSessions();
stock.getLocations();

</script>

<template>
  <van-loading v-if="ui.isLoading" vertical/>
  <van-swipe-cell v-else v-for="row of session.sessionList" :key="session.id">
    <van-cell is-link :title="row.name" :label="row.subname" @click="onSelectSession(row)"/>
    <template #right>
      <van-button square type="danger" text="Delete" class="delete-button" @click="session.deleteSession(row.id)"/>
    </template>
  </van-swipe-cell>
  <van-action-bar>
    <van-loading v-if="ui.isLoading" vertical/>
    <van-action-bar-button
        v-else
        type="success"
        text="Create new session"
        @click="show = true"
    />
    <van-action-sheet v-model:show="show" :actions="stock.locations" @select="onSelect" description="Select location"/>
  </van-action-bar>
</template>
