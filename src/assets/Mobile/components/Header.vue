<script setup>
import useUiStore from "@/stores/ui";
import useUserStore from "@/stores/user";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useTaskStore from "@/stores/task";
import useCompleteStore from "@/stores/complete";

const ui = useUiStore();
const user = useUserStore();
const stock = useStockStore();
const session = useSessionStore();
const task = useTaskStore();
const complete = useCompleteStore();

</script>

<template>
  <van-nav-bar>
    <template #title>
      <van-image src="https://hipanel.advancedhosting.com/assets/37f28765/logo_white_login.svg"/>
    </template>
    <template #right>
      <van-icon name="friends-o" color="#000000"/>&nbsp;{{ user.username }}
    </template>
  </van-nav-bar>
  <van-cell-group v-if="complete.isCompleted === false">
    <van-cell v-if="session.session" title="Session" :value="session.session.name"/>
    <van-cell v-if="stock.location" title="Location" :value="stock.location.name"/>
    <van-swipe-cell v-if="stock.destination">
      <van-cell :border="false" icon="edit" title="Destination" :value="stock.destination.name"/>
      <template #right>
        <van-button square type="warning" text="Clear" @click="stock.resetDestination"/>
      </template>
    </van-swipe-cell>
    <van-swipe-cell v-if="task.id">
      <van-cell :border="false" icon="edit" title="Task ID" :value="task.id"/>
      <template #right>
        <van-button square type="warning" text="Clear" @click="task.reset"/>
      </template>
    </van-swipe-cell>
    <van-swipe-cell v-if="user.personalId">
      <van-cell :border="false" icon="edit" title="Personal ID" :value="user.personalId"/>
      <template #right>
        <van-button square type="warning" text="Clear" @click="user.reset"/>
      </template>
    </van-swipe-cell>
  </van-cell-group>
</template>

<style scoped>
.van-image {
  filter: invert(100%);
}
</style>
