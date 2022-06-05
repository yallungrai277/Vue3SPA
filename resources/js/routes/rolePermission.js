import { authenticated, isAdmin } from "./guards";
import Authenticated from "@/layouts/Authenticated.vue";

import RolePermissionIndex from "@/pages/role-permission/Index";

export default [
    {
        path: "/role-permission",
        component: RolePermissionIndex,
        name: "rolePermissions.index",
        meta: {
            layout: Authenticated,
            title: "Role Permission",
        },
        beforeEnter: [authenticated, isAdmin],
    },
];
