import { authenticated } from "./guards";
import Authenticated from "@/layouts/Authenticated.vue";

import PostsIndex from "@/pages/posts/Index";
import PostsCreate from "@/pages/posts/Create";
import PostsEdit from "@/pages/posts/Edit";

export default [
    {
        path: "/posts",
        component: PostsIndex,
        name: "posts.index",
        meta: {
            layout: Authenticated,
            title: "Posts",
        },
        beforeEnter: [authenticated],
    },
    {
        path: "/posts/create",
        component: PostsCreate,
        name: "posts.create",
        meta: {
            layout: Authenticated,
            title: "Create Post",
        },
        beforeEnter: [authenticated],
    },
    {
        path: "/posts/edit/:id",
        component: PostsEdit,
        name: "posts.edit",
        meta: {
            layout: Authenticated,
            title: "Edit Post",
        },
        beforeEnter: [authenticated],
    },
];
