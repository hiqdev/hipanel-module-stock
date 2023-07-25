<script setup>
import { watch, nextTick } from "vue";
import { useRouter } from "vue-router";
import debounce from "lodash/debounce";
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

const router = useRouter();
const ui = useUiStore();
const stock = useStockStore();
const resolver = useResolverStore();
const complete = useCompleteStore();

watch(() => resolver.resolved, (newVal, prevVal) => {
  if (newVal === true) {
    showNotify({ type: "success", message: "Resolved" });
    resolver.code = null;
  } else if (newVal === false) {
    showNotify({ type: "danger", message: "Code is out of found" });
  }
});

watch(() => ui.isLoading, (newVal, prevVal) => {
  if (newVal === true) {
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

function onScan() {
  alert("Scan button pressed");
}

const onInput = debounce(() => {
  resolver.resolve();
}, 300);

function onProceed() {
  router.push({ name: "complete" });
}

function onBack() {
  stock.resetWithLocation();
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
        @input="onInput"
    >
<!--      <template #button>-->
<!--        <van-button icon="scan" size="small" type="default" @click="onScan"/>-->
<!--      </template>-->
    </van-field>
    <van-action-bar-icon v-if="complete.canBeCompleted" icon="arrow" @click="onProceed"/>
  </van-action-bar>
  <Informer/>
</template>
