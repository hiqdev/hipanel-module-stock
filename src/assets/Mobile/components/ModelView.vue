<script setup>
import useResolverStore from "@/stores/resolver";

const resolver = useResolverStore();

</script>

<template>

  <table class="table" v-if="resolver.resolvedModel">
    <thead>
    <tr>
      <th scope="col">Serial</th>
      <th scope="col">Place</th>
      <th scope="col">Status</th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="part of resolver.resolvedModel.parts" :key="part.id">
      <td>{{ part.serial }}</td>
      <td>{{ part.dst_name }}</td>
      <td>{{ part.move_type_label }}</td>
    </tr>
    </tbody>
  </table>

</template>
