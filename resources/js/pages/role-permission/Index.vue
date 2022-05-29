<script setup>
import { ROLES } from "@/constants";
import { onMounted, ref } from "vue";
import useRolePermissions from "@/composables/rolePermission";

const {
  roles,
  getPermissions,
  getRolePermissions,
  permissions,
  validationErrors,
  isFormSubmitting,
  updateRolePermissions,
} = useRolePermissions();
const form = ref({
  roles_permission: {},
});

onMounted(() => {
  getPermissions();
  getRolePermissions().then(() => {
    roles.value
      .map(function (role) {
        return {
          roleId: role.id,
          permissionIds: role.permissions.map((permission) => permission.id),
        };
      })
      .forEach((value) => {
        form.value.roles_permission[value.roleId] = value.permissionIds;
      });
  });
});
</script>
<template>
  <h3>Update Permissions</h3>
  <div
    class="my-4 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4"
    role="alert"
  >
    <p class="font-bold">Important message</p>
    <p>You cannot change and update admin permissions.</p>
  </div>

  <div
    class="
      bg-red-100
      border border-red-400
      text-red-700
      px-4
      py-3
      rounded
      relative
    "
    role="alert"
    v-if="Object.keys(validationErrors).length"
  >
    <p v-for="(validationError, index) in validationErrors" :key="index">
      {{ validationError[0] }}
    </p>
  </div>

  <form @submit.prevent="updateRolePermissions(form)">
    <div v-for="role in roles" :key="`role-${role.id}`" class="mt-2">
      {{ role.name }}
      <div>
        <div
          v-for="permission in permissions"
          :key="`permission-${permission.id}`"
        >
          <template v-if="role.name == ROLES.ADMIN_ROLE">
            <input
              type="checkbox"
              class="border border-gray-300 rounded-sm"
              v-model="form.roles_permission[role.id]"
              :value="permission.id"
              disabled
            />&nbsp;{{ permission.name }}&nbsp;&nbsp;
          </template>
          <template v-else>
            <input
              type="checkbox"
              class="border border-gray-300 rounded-sm"
              v-model="form.roles_permission[role.id]"
              :value="permission.id"
            />&nbsp;{{ permission.name }}&nbsp;&nbsp;
          </template>
        </div>
      </div>
    </div>
    <div class="mt-4">
      <button
        :disabled="isFormSubmitting"
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
          v-show="isFormSubmitting"
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
        <span v-if="isFormSubmitting">Processing...</span>
        <span v-else>Save</span>
      </button>
    </div>
  </form>
</template>
