import { ref } from "vue";

export default function useRolePermissions() {
    const roles = ref([]);
    const permissions = ref([]);
    const isFormSubmitting = ref(false);
    const validationErrors = ref({});

    const getPermissions = async () => {
        try {
            const res = await axios.get("api/permissions");
            permissions.value = res.data.data;
            return res;
        } catch (err) {
            swal({
                icon: "error",
                title: err.response?.data?.message,
            });
            throw err;
        }
    };

    const getRolePermissions = async () => {
        try {
            const res = await axios.get("api/role-permissions");
            roles.value = res.data.data;
            return res;
        } catch (err) {
            swal({
                icon: "error",
                title: err.response?.data?.message,
            });
            throw err;
        }
    };

    const updateRolePermissions = async (payload) => {
        if (isFormSubmitting.value) return;
        isFormSubmitting.value = true;
        validationErrors.value = {};

        return axios
            .put("api/role-permissions", payload)
            .then((res) => {
                swal({
                    icon: "success",
                    title: "Permissions updated successfully",
                });
            })
            .catch((err) => {
                if (err?.response?.status === 422) {
                    validationErrors.value = err.response.data.errors;
                }
            })
            .finally(() => {
                isFormSubmitting.value = false;
            });
    };

    return {
        roles,
        permissions,
        isFormSubmitting,
        validationErrors,
        getPermissions,
        getRolePermissions,
        updateRolePermissions,
    };
}
