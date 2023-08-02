<script setup>
import { ref, watch, nextTick, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";

import { showNotify, showLoadingToast, closeToast, showConfirmDialog } from "vant";
import "vant/es/notify/style";
import "vant/es/toast/style";
import "vant/es/dialog/style";

import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useUiStore from "@/stores/ui";
import useResolverStore from "@/stores/resolver";
import useCompleteStore from "@/stores/complete";
import Informer from "@/components/Informer.vue";
import { debounce } from "lodash/function";

const url = __scannerSrc;
const router = useRouter();
const ui = useUiStore();
const stock = useStockStore();
const resolver = useResolverStore();
const complete = useCompleteStore();

const flag = ref(false);

const myFocus = event => {
  const element = document.getElementById("any-code");
  const isFocused = (document.activeElement === element);
  if (!isFocused) {
    if (flag.value === true) {
      element.value = "";
      flag.value = false;
    }
    element.focus();
  }
};
onMounted(() => {
  document.addEventListener("keydown", myFocus);
});
onUnmounted(() => {
  document.removeEventListener("keydown", myFocus);
});

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === false) {
    showNotify({ type: "danger", message: "Code is out of found" });
  }
});

watch(() => ui.isLoading, (newVal, prevVal) => {
  const element = document.getElementById("any-code");
  if (newVal === true) {
    element.setAttribute("inputmode", "none");
    showLoadingToast({
      duration: 0,
      message: "Resolving...",
      forbidClick: true,
    });
  } else {
    nextTick(() => {
      closeToast();
    });
  }
});

watch(() => stock.serialDuplicate, (newVal, prevVal) => {
  if (newVal !== null) {
    showConfirmDialog({
      title: resolver.code,
      message: "This serial number is already in a session!",
      showCancelButton: true,
      confirmButtonColor: "#ee0a24",
      confirmButtonText: "Remove",
      cancelButtonText: "Ignore",
      zIndex: "2004",
    }).then(async () => {
      await stock.removeDuplicate();
    }).catch(() => {
      stock.serialDuplicate = null;
    });
  }
});

const handleResolve = debounce(async (event) => {
  await resolver.resolve();
  document.getElementById("any-code").blur();
  flag.value = true;
});

function onProceed() {
  router.push({ name: "complete" });
}

function onBack() {
  window.location.reload();
}

</script>

<template>
  <van-cell-group inset v-if="stock.serials.length > 0" v-for="model of stock.modelsWithSerials()">
    <template #title>
      <van-space>
        <span class="custom-title">{{ model.partno }}</span>
        <van-tag plain>{{ stock.inModelCount(model.id) }}</van-tag>
      </van-space>
    </template>
    <van-swipe-cell v-for="part of model.parts" :key="part.id">
      <van-cell :title="part.serial" :label="part.model_label"/>
      <template #right>
        <van-button square type="danger" class="delete-button" text="Delete" @click="stock.removeSerial(part)"/>
      </template>
    </van-swipe-cell>
  </van-cell-group>
  <van-image v-else fit="cover" position="center" :src="url"/>

  <div class="van-clearfix"></div>

  <van-action-bar>
    <van-action-bar-icon icon="arrow-left" @click="onBack"/>
    <van-field
        id="any-code"
        v-model.trim="resolver.code"
        :border="false"
        autofocus
        autocapitalize="off"
        tabindex="0"
        placeholder="Enter or scan any code"
        input-align="center"
        @keyup.enter="handleResolve"
    />
    <van-action-bar-icon v-if="complete.canBeCompleted" icon="arrow" @click="onProceed"/>
  </van-action-bar>

  <Informer/>

</template>
