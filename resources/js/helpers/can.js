import store from "@/store";

const can = (permission = "") => {
    const userPermissions = store.getters["auth/permissions"];
    if (!Array.isArray(userPermissions)) return false;
    return userPermissions.includes(permission);
};

export { can };
