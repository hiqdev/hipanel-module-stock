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
  <van-cell-group>
    <van-cell v-if="session.session" title="Session" :value="session.session.name"/>
    <van-cell v-if="stock.location" title="Location" :value="stock.location.name"/>
    <van-field
        v-if="stock.destination"
        v-model="stock.destination.name"
        label="PUT"
        right-icon="cross"
        input-align="right"
        readonly
        clickable
        @click="stock.resetDestination"
    />
    <van-field
        v-if="task.id"
        v-model="task.id"
        label="Task ID"
        right-icon="cross"
        input-align="right"
        readonly
        clickable
        @click="task.reset"
    />
    <van-field
        v-if="user.personalId"
        v-model="user.personalId"
        label="Personal ID"
        right-icon="cross"
        input-align="right"
        readonly
        clickable
        @click="user.reset"
    />
  </van-cell-group>
</template>

<style scoped>
.van-image {
  filter: invert(100%);
}
</style>
