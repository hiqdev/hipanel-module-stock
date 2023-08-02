<script setup>
import split from "lodash/split";
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useTaskStore from "@/stores/task";
import useCompleteStore from "@/stores/complete";

const url = __logoSrc;
const ui = useUiStore();
const user = useUserStore();
const stock = useStockStore();
const session = useSessionStore();
const task = useTaskStore();
const complete = useCompleteStore();

function onTaskTransition(url) {
  window.open(url, "_blank").focus();
}

</script>

<template>
  <van-nav-bar>
    <template #left>
      <span v-if="stock.location" style="color: #666666">
        <van-icon name="location-o" color="#666666"/>&nbsp;{{ stock.location.name.split(":").slice(-1).join() }}
      </span>
    </template>
    <template #title>
      <van-image :src="url"/>
    </template>
    <template #right>
      <van-icon name="friends-o" color="#000000"/>&nbsp;{{ user.username }}
    </template>
  </van-nav-bar>
  <van-cell-group v-if="complete.isCompleted === false">
    <van-swipe-cell v-if="stock.destination">
      <van-cell label="Destination" :title="stock.destination.name"/>
      <template #right>
        <van-button square type="danger" class="delete-button" text="Delete" @click="stock.resetDestination"/>
      </template>
    </van-swipe-cell>
    <van-swipe-cell v-if="task.url">
      <van-cell is-link :title="task.name" label="Task" @click="onTaskTransition(task.url)"/>
      <template #right>
        <van-button square type="danger" class="delete-button" text="Delete" @click="task.reset"/>
      </template>
    </van-swipe-cell>
    <van-swipe-cell v-if="user.personalId">
      <van-cell :title="user.personalId" label="Personal ID"/>
      <template #right>
        <van-button square type="danger" class="delete-button" text="Delete" @click="user.reset"/>
      </template>
    </van-swipe-cell>
  </van-cell-group>
</template>

<style scoped>
.van-image {
  filter: invert(100%);
}
</style>
