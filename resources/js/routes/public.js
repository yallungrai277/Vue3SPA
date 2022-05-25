import AppIndex from "../pages/public/Index.vue";

import Guest from "../layouts/Guest.vue";

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
