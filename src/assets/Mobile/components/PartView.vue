<script setup>
import { ref } from "vue";
import useResolverStore from "@/stores/resolver";

const resolver = useResolverStore();

</script>

<template>
  <van-cell-group v-if="resolver.resolvedPart">
    <van-cell title="Type" :value="resolver.resolvedPart.model_type_label"/>
    <van-cell title="Manufacturer" :value="resolver.resolvedPart.model_brand_label"/>
    <van-cell title="Part No." :value="resolver.resolvedPart.partno"/>
    <van-cell title="Model" :value="resolver.resolvedPart.model_label"/>
    <van-cell title="Place" :value="resolver.resolvedPart.dst_name"/>
  </van-cell-group>
</template>
