<script setup>
import { useStore } from "vuex";

const store = useStore();
const authenticated = store.getters["auth/authenticated"];
</script>
<template>
  <h2 class="text-center">Welcome to SPA</h2>
  <div class="mt-8">
    <template v-if="authenticated">
      <router-link :to="{ name: 'dashboard' }">Go to dashboard</router-link>
    </template>
    <template v-else>
      <router-link :to="{ name: 'login' }">Login</router-link><br />
      <router-link :to="{ name: 'register' }">Sign Up</router-link>
    </template>
  </div>
</template>

