import { createRouter, createWebHistory } from "vue-router";
import publicRoutes from "./public";
import authRoutes from "./auth";
import postRoutes from "./posts";
import rolePermission from "./rolePermission";
import errors from "./errors";

const routes = [
    ...publicRoutes,
    ...authRoutes,
    ...postRoutes,
    ...rolePermission,
    ...errors,
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    document.title = `${to.meta?.title ?? "App"}`;
    next();
});

export default router;
