<script setup>
import useResolverStore from "@/stores/resolver";

const resolver = useResolverStore();

</script>

<template>

  <table class="table" v-if="resolver.resolvedOrder">
    <thead>
    <tr>
      <th scope="col">Type</th>
      <th scope="col">Manufacturer</th>
      <th scope="col">Serial</th>
      <th scope="col">Part No.</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="part of resolver.resolvedOrder.parts" :key="part.id">
      <td>{{ part.model_type_label }}</td>
      <td>{{ part.model_brand_label }}</td>
      <td>{{ part.serial }}</td>
      <td>{{ part.partno }}</td>
    </tr>
    </tbody>
  </table>

</template>
