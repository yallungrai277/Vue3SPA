<script setup>
import { useStore } from "vuex";

const store = useStore();
const authenticated = store.getters["auth/authenticated"];
</script>
<template>
  <template v-if="authenticated">
    <router-link :to="{ name: 'posts.index' }">Go to dashboard</router-link>
  </template>
  <template v-else>
    <router-link :to="{ name: 'login' }">Login</router-link><br />
    <router-link :to="{ name: 'register' }">Sign Up</router-link>
  </template>
</template>

