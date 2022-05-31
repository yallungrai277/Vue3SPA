import Errors from "@/layouts/Errors";
import PageNotFound from "@/pages/errors/404";
import Forbidden from "@/pages/errors/403";
import ServerError from "@/pages/errors/500";

export default [
    {
        path: "/500",
        component: ServerError,
        name: "500",
        meta: {
            layout: Errors,
            title: "500",
        },
    },
    {
        path: "/403",
        component: Forbidden,
        name: "403",
        meta: {
            layout: Errors,
            title: "403",
        },
    },
    {
        path: "/:pathMatch(.*)*",
        component: PageNotFound,
        name: "404",
        meta: {
            layout: Errors,
            title: "404",
        },
    },
];
