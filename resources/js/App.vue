<script setup>
import DefaultLayout from "./layouts/Default";
</script>
<template>
  <component :is="$route.meta.layout || DefaultLayout">
    <router-view />
  </component>
</template>
