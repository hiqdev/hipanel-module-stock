<script setup>
const props = defineProps({
  part: Object,
});

const part = props.part;
</script>

<template>
  <van-cell-group>
    <van-cell title="Type" :value="part.model_type_label"/>
    <van-cell title="Manufacturer" :value="part.model_brand_label"/>
    <van-cell title="Part No." :value="part.partno"/>
    <van-cell title="Model" :value="part.model_label"/>
    <van-cell title="Place" :value="part.dst_name"/>
  </van-cell-group>
</template>
