<script setup>
import { ref, watch, nextTick } from "vue";
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
    showNotify({ type: "success", message: "Code resolved" });
    resolver.code = null;
  } else if (newVal === false) {
    showNotify({ type: "danger", message: "Code is out of found" });
  }
});

watch(() => ui.isLoading, (newVal, prevVal) => {
  if (newVal === true) {
    showLoadingToast({
      duration: 0,
      message: "Loading...",
      forbidClick: true,
    });
  } else {
    nextTick(() => {
      closeToast();
    });
  }
});

watch(() => stock.isFinished, (newVal, prevVal) => {
  if (newVal === true) {
    nextTick(() => {
      router.push({ name: "complete" });
    });
  }
});

watch(() => stock.hasError, (newVal, prevVal) => {
  if (newVal === true) {
    showNotify({ type: "danger", message: stock.errorMessage });
  }
});

function onScan() {
  alert("Scan button pressed");
}

const onInput = debounce(() => {
  resolver.resolve();
}, 300);

function onProceed() {
  complete.complete();
}

function onBack() {
  stock.resetWithLocation();
  resolver.code = null;
  router.push({ name: "location" });
}
</script>
<template>
  <van-cell-group inset title="	E5-2620 (11)">
    <van-swipe-cell v-for="part of stock.parts" :key="part.id">
      <van-cell :border="false" :title="part.model_label" :value="part.serial"/>
      <template #right>
        <van-button square type="danger" text="Delete" @click="stock.removePart(part)"/>
      </template>
    </van-swipe-cell>
  </van-cell-group>

  <div class="van-clearfix"></div>

  <van-action-bar>
    <van-action-bar-icon icon="arrow-left" @click="onBack"/>
    <van-field
        id="any-code"
        v-model="resolver.code"
        :border="false"
        autofocus
        tabindex="0"
        placeholder="Enter or scan any code"
        input-align="center"
        @input="onInput"
    >
      <template #button>
        <van-button icon="scan" size="small" type="default" @click="onScan"/>
      </template>
    </van-field>
    <van-action-bar-icon v-if="complete.canBeCompleted" icon="arrow" @click="onProceed"/>
  </van-action-bar>

  <Informer/>
</template>
