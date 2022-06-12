import { ref } from "vue";
export default function userPermission() {
    const permissions = ref({});

    const getUserPermission = async () => {
        const res = await axios.get("api/user-permissions");
        permissions.value = res.data.data;
        return res.data.data;
    };

    return {
        getUserPermission,
        permissions,
    };
}
