<script setup>
import { reactive } from "vue";
import { useRoute, useRouter } from "vue-router";
import authRouterChange from "@/composables/authRouterChange";
import { useActions, useGetters, useMutations } from "vuex-composition-helpers";
const route = useRoute();
const router = useRouter();

const form = reactive({
  password: "",
  password_confirmation: "",
  token: route.params.token,
});

const { resetPassword } = useActions({
  resetPassword: "auth/resetPassword",
});

const { validationErrors, isSubmitting } = useGetters({
  isSubmitting: "auth/isSubmitting",
  validationErrors: "auth/validationErrors",
});

const { setSubmitting } = useMutations({
  setSubmitting: "auth/SET_SUBMITTING",
});

const submit = () => {
  resetPassword(form)
    .then(() => {
      form.password = "";
      form.password_confirmation = "";
      router.push({ name: "login" });
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
      <!-- password -->
      <div>
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
          autofocus
          autocomplete="username"
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

      <div>
        <label
          for="password_confirmation"
          class="block font-medium text-sm text-gray-700"
        >
          Password Confirmation
        </label>
        <input
          v-model="form.password_confirmation"
          id="password_confirmation"
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
          autofocus
          autocomplete="new-password"
        />
        <!-- Validation Errors -->
        <div class="text-red-600 mt-1">
          <div
            v-for="(message, index) in validationErrors?.password_confirmation"
            :key="`password_confirmation-${index}`"
          >
            {{ message }}
          </div>
        </div>
        <div class="text-red-600 mt-1">
          <div
            v-for="(message, index) in validationErrors?.token"
            :key="`token-${index}`"
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
          Reset Password
        </button>
      </div>
    </div>
  </form>
</template>
