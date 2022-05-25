<script setup>
import { useRoute, useRouter } from "vue-router";
import { useActions } from "vuex-composition-helpers";
import authRouterChange from "@/composables/authRouterChange";

const route = useRoute();
const router = useRouter();

const { emailVerify } = useActions({
  emailVerify: "auth/emailVerify",
});

emailVerify(route.params.token)
  .then((res) => {
    console.log(res);
  })
  .finally(() => {
    router.push({ name: "login" });
  });

authRouterChange();
</script>
<template></template>
