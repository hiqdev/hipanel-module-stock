<template>
  <van-notice-bar scrollable color="#0a7d41" background="#07c16026" left-icon="info-o" text="Completed!"/>
</template>

<script setup>
import { useRouter } from "vue-router";

const router = useRouter();

function onProceed() {
  alert("proceed");
}
</script>
