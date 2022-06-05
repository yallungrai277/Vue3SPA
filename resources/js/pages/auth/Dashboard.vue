<script setup>
import { ref } from "vue";
import { useStore } from "vuex";

const store = useStore();
const user = store.getters["auth/user"];

const profileForm = ref({
  name: user.name,
  email: user.email,
});

const passwordForm = ref({
  password: "",
  password_confirmation: "",
});

const isProfileFormSubmitting = ref(false);
const isPasswordFormSubmitting = ref(false);

const profileFormValidationErrors = ref({});
const passwordFormValidationErrors = ref({});

function updateProfile() {
  if (isProfileFormSubmitting.value) return;
  isProfileFormSubmitting.value = true;
  profileFormValidationErrors.value = {};
  axios
    .put("api/profile/update", profileForm.value)
    .then((res) => {
      store.commit("auth/SET_USER", res.data);
      swal({
        icon: "success",
        title: "Your profile has been updated",
      });
    })
    .catch((err) => {
      console.log(err);
      if (err.response?.status === 422) {
        profileFormValidationErrors.value = err.response.data.errors;
      } else {
        swal({
          icon: "error",
          title: "Oops some errors occured",
        });
      }
    })
    .finally(() => {
      isProfileFormSubmitting.value = false;
    });
}

function updatePassword() {
  if (isPasswordFormSubmitting.value) return;
  isPasswordFormSubmitting.value = true;
  passwordFormValidationErrors.value = {};
  axios
    .put("api/profile/change-password", passwordForm.value)
    .then((res) => {
      swal({
        icon: "success",
        title: "Your password has been updated",
      });
      passwordForm.value = {};
    })
    .catch((err) => {
      if (err.response?.status === 422) {
        passwordFormValidationErrors.value = err.response.data.errors;
      } else {
        swal({
          icon: "error",
          title: "Oops some errors occured",
        });
      }
    })
    .finally(() => {
      isPasswordFormSubmitting.value = false;
    });
}
</script>
<template>
  Welcome to your dashboard
  <div class="grid grid-cols-2 gap-3 mt-6">
    <div>
      <p class="mb-6">Update Profile</p>
      <form @submit.prevent="updateProfile">
        <!-- Name -->
        <div>
          <label for="name" class="block font-medium text-sm text-gray-700">
            Name
          </label>
          <input
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
            v-model="profileForm.name"
          />
          <div class="text-red-600 mt-1">
            <div
              v-for="(message, index) in profileFormValidationErrors?.name"
              :key="`name-${index}`"
            >
              {{ message }}
            </div>
          </div>
        </div>
        <div>
          <label for="email" class="block font-medium text-sm text-gray-700">
            Email
          </label>
          <input
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
            v-model="profileForm.email"
          />
          <div class="text-red-600 mt-1">
            <div
              v-for="(message, index) in profileFormValidationErrors?.email"
              :key="`email-${index}`"
            >
              {{ message }}
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4">
          <button
            :disabled="isProfileFormSubmitting"
            class="
              inline-flex
              items-center
              px-3
              py-2
              bg-blue-600
              text-white
              rounded
              disabled:opacity-75 disabled:cursor-not-allowed
            "
          >
            <div
              v-show="isProfileFormSubmitting"
              class="
                inline-block
                animate-spin
                w-4
                h-4
                mr-2
                border-t-2
                border-t-white
                border-r-2
                border-r-white
                border-b-2
                border-b-white
                border-l-2
                border-l-blue-600
                rounded-full
              "
            ></div>
            <span v-if="isProfileFormSubmitting">Processing...</span>
            <span v-else>Save</span>
          </button>
        </div>
      </form>
    </div>
    <div>
      <p class="mb-6">Change Password</p>
      <form @submit.prevent="updatePassword">
        <!-- Password -->
        <div>
          <label for="password" class="block font-medium text-sm text-gray-700">
            Password
          </label>
          <input
            id="password"
            type="password"
            autocomplete="off"
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
            v-model="passwordForm.password"
          />
          <div class="text-red-600 mt-1">
            <div
              v-for="(message, index) in passwordFormValidationErrors?.password"
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
            id="password_confirmation"
            autocomplete="off"
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
            v-model="passwordForm.password_confirmation"
          />
          <div class="text-red-600 mt-1">
            <div
              v-for="(
                message, index
              ) in passwordFormValidationErrors?.password_confirmation"
              :key="`password_confirmation-${index}`"
            >
              {{ message }}
            </div>
          </div>
        </div>

        <!-- Buttons -->
        <div class="mt-4">
          <button
            :disabled="isPasswordFormSubmitting"
            class="
              inline-flex
              items-center
              px-3
              py-2
              bg-blue-600
              text-white
              rounded
              disabled:opacity-75 disabled:cursor-not-allowed
            "
          >
            <div
              v-show="isPasswordFormSubmitting"
              class="
                inline-block
                animate-spin
                w-4
                h-4
                mr-2
                border-t-2
                border-t-white
                border-r-2
                border-r-white
                border-b-2
                border-b-white
                border-l-2
                border-l-blue-600
                rounded-full
              "
            ></div>
            <span v-if="isPasswordFormSubmitting">Processing...</span>
            <span v-else>Save</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
