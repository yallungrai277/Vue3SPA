<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import { useActions, useGetters, useMutations } from "vuex-composition-helpers";
import authRouterChange from "@/composables/authRouterChange";

const router = useRouter();
const form = reactive({
  name: "",
  email: "",
  password: "",
});

const { register } = useActions({
  register: "auth/register",
});

const { validationErrors, isSubmitting } = useGetters({
  validationErrors: "auth/validationErrors",
  isSubmitting: "auth/isSubmitting",
});

const { setSubmitting } = useMutations({
  setSubmitting: "auth/SET_SUBMITTING",
});

const registration = () => {
  register(form)
    .then(() => {
      Object.keys(form).forEach(function (key) {
        form[key] = "";
      });
      router.push({ name: "login" });
    })
    .finally(() => {
      setSubmitting(false);
    });
};

authRouterChange();
</script>
<template>
  <form @submit.prevent="registration()">
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
      <!-- Name -->
      <div>
        <label for="name" class="block font-medium text-sm text-gray-700">
          Name
        </label>
        <input
          v-model="form.name"
          id="name"
          type="text"
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
            v-for="(message, index) in validationErrors?.name"
            :key="`name-${index}`"
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
          Login
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
        >
          Sign Up
        </button>
      </div>
    </div>
  </form>
</template>
