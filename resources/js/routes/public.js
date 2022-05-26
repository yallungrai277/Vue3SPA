import Guest from "../layouts/Guest.vue";
import AppIndex from "../pages/public/Index.vue";

export default [
    {
        path: "/",
        component: AppIndex,
        name: "app.index",
        meta: {
            layout: Guest,
            title: "App",
        },
    },
];
