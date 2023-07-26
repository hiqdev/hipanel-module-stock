<script setup>
import { ref } from "vue";

import Header from "@/components/Header.vue";
import useUserStore from "@/stores/user";
import useUiStore from "@/stores/ui";

const uiStore = useUiStore();
const userStore = useUserStore();

userStore.getUser();

</script>

<template>
  <van-config-provider :theme="uiStore.theme">
    <Header/>
    <router-view></router-view>
  </van-config-provider>
</template>
