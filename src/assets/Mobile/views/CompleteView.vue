<template>
  <div v-if="complete.isCompleted">
    <van-notice-bar text="Completed!"/>
    <van-action-bar>
      <van-action-bar-button type="success" text="Start again" @click="onStartAgain"/>
    </van-action-bar>
  </div>
  <div v-else>
    <van-cell v-for="model of stock.modelsWithSerials()" :title="model.partno" :key="model.id">
      <template #value>
        <van-space>
          <van-icon name="arrow" class="search-icon"/>
          <span>
          {{ stock.destination ? stock.destination.name : "" }} <van-tag plain>{{ model.parts.length }} parts</van-tag>
        </span>
        </van-space>
      </template>
    </van-cell>
    <van-action-bar>
      <van-action-bar-icon icon="arrow-left" @click="onBack"/>
      <van-field
          v-model.trim="stock.comment"
          :border="false"
          autofocus
          tabindex="0"
          placeholder="Comment..."
          input-align="center"
      >
      </van-field>
      <van-action-bar-icon icon="arrow" @click="onComplete"/>
    </van-action-bar>
  </div>
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
    complete.setComplete();
    nextTick(() => {
      closeToast();
    });
  }
});

watch(() => complete.canBeCompleted, (newVal, prevVal) => {
  if (newVal === false) {
    nextTick(() => {
      router.push({ name: "any-code" });
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
}

function onBack() {
  router.push({ name: "any-code" });
}

function onStartAgain() {
  window.location.reload();
}

</script>

<style>
.van-notice-bar__wrap {
  justify-content: center;
}
</style>
