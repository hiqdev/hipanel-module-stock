<script setup>
import { watch, nextTick, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import { showNotify } from "vant";
import "vant/es/notify/style";
import { showLoadingToast, closeToast } from "vant";
import "vant/es/toast/style";
import useStockStore from "@/stores/stock";
import useSessionStore from "@/stores/session";
import useUiStore from "@/stores/ui";
import useResolverStore from "@/stores/resolver";
import useCompleteStore from "@/stores/complete";
import Informer from "@/components/Informer.vue";
import { debounce } from "lodash/function";

const router = useRouter();
const ui = useUiStore();
const stock = useStockStore();
const resolver = useResolverStore();
const complete = useCompleteStore();

let intervalId;
onMounted(() => {
  intervalId = setInterval(() => {
    const element = document.getElementById("any-code");
    const isFocused = (document.activeElement === element);
    if (!isFocused) {
      element.focus();
    }
  }, 1000);
});
onUnmounted(() => clearInterval(intervalId));

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === true) {
    showNotify({ type: "success", message: "Resolved" });
  } else if (newVal === false) {
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

const handleResolve = debounce(() => {
  resolver.resolve();
});

function onProceed() {
  router.push({ name: "complete" });
}

function onBack() {
  stock.resetSerials();
  stock.resetLocation();
  resolver.code = null;
  router.push({ name: "location" });
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
      <van-cell :border="false" :title="part.model_label" :value="part.serial"/>
      <template #right>
        <van-button square type="danger" text="Delete" @click="stock.removeSerial(part)"/>
      </template>
    </van-swipe-cell>
  </van-cell-group>

  <div class="van-clearfix"></div>

  <van-action-bar>
    <van-action-bar-icon icon="arrow-left" @click="onBack"/>
    <van-field
        id="any-code"
        v-model.trim="resolver.code"
        :border="false"
        autofocus
        tabindex="0"
        placeholder="Enter or scan any code"
        input-align="center"
        @keyup.enter="handleResolve"
    />
    <van-action-bar-icon v-if="complete.canBeCompleted" icon="arrow" @click="onProceed"/>
  </van-action-bar>

  <Informer/>

</template>
