<script setup>
import { reactive, ref } from "vue";
import authRouterChange from "@/composables/authRouterChange";
import { useActions, useGetters, useMutations } from "vuex-composition-helpers";

const form = reactive({
  email: "",
  password: "",
});

const { validationErrors, isSubmitting } = useGetters({
  validationErrors: "auth/validationErrors",
  isSubmitting: "auth/isSubmitting",
});

const { login } = useActions({
  login: "auth/login",
});

const { setSubmitting } = useMutations({
  setSubmitting: "auth/SET_SUBMITTING",
});

const submit = () => {
  login(form)
    .then(() => {})
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
      <!-- Password -->
      <div class="mt-4">
        <label for="password" class="block font-medium text-sm text-gray-700">
          Password
        </label>
        <input
          v-model="form.password"
          id="password"
          type="password"
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
          autocomplete="current-password"
        />
        <!-- Validation Errors -->
        <div class="text-red-600 mt-1">
          <div
            v-for="(message, index) in validationErrors?.password"
            :key="`password-${index}`"
          >
            {{ message }}
          </div>
        </div>
      </div>
      <!-- Remember me -->
      <div class="block mt-4">
        <!-- <label class="flex items-center">
          <input
            type="checkbox"
            name="remember"
            v-model="form.remember"
            class="
              rounded
              border-gray-300
              text-indigo-600
              shadow-sm
              focus:border-indigo-300
              focus:ring
              focus:ring-indigo-200
              focus:ring-opacity-50
            "
          />
          <span class="ml-2 text-sm text-gray-600">Remember me</span>
        </label> -->
        <label class="flex items-center">
          <router-link
            class="ml-2 text-sm text-gray-600"
            :to="{ name: 'verify.email' }"
          >
            Verify Email ?
          </router-link>
        </label>
        <label class="flex items-center">
          <router-link
            class="ml-2 text-sm text-gray-600"
            :to="{ name: 'forgot.password' }"
          >
            Forgot Password ?
          </router-link>
        </label>
      </div>

      <!-- Buttons -->
      <div class="flex items-center justify-end mt-4">
        <router-link
          :to="{ name: 'register' }"
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
          Sign Up
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
          Log in
        </button>
      </div>
    </div>
  </form>
</template>
