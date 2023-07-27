<script setup>
import useStockStore from "@/stores/stock";

const props = defineProps({
  order: Object,
});

const stock = useStockStore();
const order = stock.orderWithParts(props.order.id);

</script>

<template>

  <table class="table">
    <thead>
    <tr>
      <th scope="col">Type</th>
      <th scope="col">Manufacturer</th>
      <th scope="col">Serial</th>
      <th scope="col">Part No.</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="part of order.parts" :key="part.id">
      <td>{{ part.model_type_label }}</td>
      <td>{{ part.model_brand_label }}</td>
      <td>{{ part.serial }}</td>
      <td>{{ part.partno }}</td>
    </tr>
    </tbody>
  </table>

</template>
