<script setup>
import useStockStore from "@/stores/stock";

const props = defineProps({
  model: Object,
});

const stock = useStockStore();
const model = stock.modelWithParts(props.model.id);

</script>

<template>

  <table class="table">
    <thead>
    <tr>
      <th scope="col">Serial</th>
      <th scope="col">Place</th>
      <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="part of model.parts" :key="part.id">
      <td>{{ part.serial }}</td>
      <td>{{ part.dst_name }}</td>
      <td>{{ part.move_type_label }}</td>
    </tr>
    </tbody>
  </table>

</template>
