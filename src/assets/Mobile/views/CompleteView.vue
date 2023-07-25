<template>
  info
  <van-action-bar v-if="complete.isCompleted === false">
    <van-action-bar-icon icon="arrow-left" @click="onBack"/>
    <van-field
        v-model.trim="stock.comment"
        :border="false"
        autofocus
        tabindex="0"
        placeholder="Add a comment if needed"
        input-align="center"
        @input="onInput"
    >
    </van-field>
    <van-action-bar-icon icon="arrow" @click="onComplete"/>
  </van-action-bar>
</template>

<script setup>
import { nextTick, ref, watch } from "vue";
import { useRouter } from "vue-router";
import { closeToast, showLoadingToast, showNotify } from "vant";
import useStockStore from "@/stores/stock";
import useCompleteStore from "@/stores/complete";
import useUiStore from "@/stores/ui";

const router = useRouter();
const stock = useStockStore();
const complete = useCompleteStore();
const ui = useUiStore();

watch(() => ui.isLoading, (newVal, prevVal) => {
  if (newVal === true) {
    showLoadingToast({
      duration: 0,
      message: "Completing...",
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

function onComplete() {
  complete.complete();
  description.value = "";
}

function onBack() {
  router.push({ name: "any-code" });
}

function onProceed() {
  alert("proceed");
}
</script>
