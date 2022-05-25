import PostsIndex from "@/pages/posts/Index";
import PostsCreate from "@/pages/posts/Create";
import PostsEdit from "@/pages/posts/Edit";

import Authenticated from "@/layouts/Authenticated.vue";

export default [
    {
        path: "/posts",
        component: PostsIndex,
        name: "posts.index",
        meta: {
            layout: Authenticated,
            title: "Posts",
        },
    },
    {
        path: "/posts/create",
        component: PostsCreate,
        name: "posts.create",
        meta: {
            layout: Authenticated,
            title: "Create Post",
        },
    },
    {
        path: "/posts/edit/:id",
        component: PostsEdit,
        name: "posts.edit",
        meta: {
            layout: Authenticated,
            title: "Edit Post",
        },
    },
];
