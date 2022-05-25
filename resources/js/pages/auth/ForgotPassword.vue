<script setup>
import { reactive } from "vue";
import authRouterChange from "@/composables/authRouterChange";
import { useActions, useGetters, useMutations } from "vuex-composition-helpers";

const form = reactive({
  email: "",
});

const { forgotPassword } = useActions({
  forgotPassword: "auth/forgotPassword",
});

const { validationErrors, isSubmitting } = useGetters({
  isSubmitting: "auth/isSubmitting",
  validationErrors: "auth/validationErrors",
});

const { setSubmitting } = useMutations({
  setSubmitting: "auth/SET_SUBMITTING",
});

const submit = () => {
  forgotPassword(form)
    .then(() => {
      form.email = "";
    })
    .finally(() => {
      setSubmitting(false);
    });
};

authRouterChange();
</script>
<template>
  <form @submit.prevent="submit">
    <div>
      <!-- Email -->
      <div>
        <label for="email" class="block font-medium text-sm text-gray-700">
          Email
        </label>
        <input
          v-model="form.email"
          id="email"
          type="email"
          class="
            block
            mt-1
            w-full
            rounded-md
            shadow-sm
            border-gray-300
            focus:border-indigo-300
            focus:ring
            focus:ring-indigo-200
            focus:ring-opacity-50
          "
          required
          autofocus
          autocomplete="username"
        />
        <!-- Validation Errors -->
        <div class="text-red-600 mt-1">
          <div
            v-for="(message, index) in validationErrors?.email"
            :key="`email-${index}`"
          >
            {{ message }}
          </div>
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex items-center justify-end mt-4">
        <router-link
          :to="{ name: 'login' }"
          class="
            inline-flex
            items-center
            px-4
            py-2
            bg-transparent
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-black
            uppercase
            tracking-widest
            active:bg-gray-900
            focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
            transition
            ease-in-out
            duration-150
            ml-4
          "
        >
          Log in
        </router-link>
        <button
          class="
            inline-flex
            items-center
            px-4
            py-2
            bg-gray-800
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-white
            uppercase
            tracking-widest
            hover:bg-gray-700
            active:bg-gray-900
            focus:outline-none focus:border-gray-900 focus:shadow-outline-gray
            transition
            ease-in-out
            duration-150
            ml-4
          "
          :class="{ 'opacity-25': isSubmitting }"
          :disabled="isSubmitting"
          type="submit"
        >
          Send Password Reset Request
        </button>
      </div>
    </div>
  </form>
</template>
