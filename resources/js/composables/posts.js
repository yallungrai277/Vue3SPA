import axios from "axios";
import { ref, inject } from "vue";
import { useRouter } from "vue-router";

export default function usePosts() {
    const posts = ref({});
    const post = ref({});
    const router = useRouter();
    const validationErrors = ref({});
    const isFormSubmitting = ref(false);
    const swal = inject("$swal");

    const getPosts = async (
        page = 1,
        searchCategory = "",
        searchId = "",
        searchTitle = "",
        searchContent = "",
        searchGlobal = "",
        orderColumn = "created_at",
        orderDirection = "desc"
    ) => {
        axios
            .get(
                `/api/posts?page=${page}
                &search_category=${searchCategory}
                &search_id=${searchId}
                &search_title=${searchTitle}
                &search_content=${searchContent}
                &search_global=${searchGlobal}
                &order_column=${orderColumn}
                &order_direction=${orderDirection}`
            )
            .then((response) => {
                posts.value = response.data;
            });
    };

    const storePosts = (post) => {
        if (isFormSubmitting.value) return;

        isFormSubmitting.value = true;
        validationErrors.value = {};

        let serializedPost = new FormData();
        for (let item in post) {
            if (post.hasOwnProperty(item)) {
                serializedPost.append(item, post[item]);
            }
        }

        axios
            .post("/api/posts", serializedPost)
            .then((res) => {
                router.push({ name: "posts.index" });
                swal({
                    icon: "success",
                    title: "Post created successfully",
                });
            })
            .catch((err) => {
                if (err.response.status == 422 && err.response?.data) {
                    validationErrors.value = err.response.data.errors;
                }
            })
            .finally(() => {
                isFormSubmitting.value = false;
            });
    };

    const updatePost = (post) => {
        if (isFormSubmitting.value) return;

        isFormSubmitting.value = true;
        validationErrors.value = {};

        axios
            .put("/api/posts/" + post.id, post)
            .then((res) => {
                post.value = res.data;
                router.push({ name: "posts.index" });
                swal({
                    icon: "success",
                    title: "Post updated successfully",
                });
            })
            .catch((err) => {
                if (err.response?.status == 422 && err.response?.data) {
                    validationErrors.value = err.response.data.errors;
                }
            })
            .finally(() => {
                isFormSubmitting.value = false;
            });
    };

    const getPost = (id) => {
        return axios
            .get("/api/posts/" + id)
            .then((res) => {
                post.value = res.data;
            })
            .catch((err) => {})
            .finally(() => {});
    };

    const deletePost = async (id) => {
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this action!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            confirmButtonColor: "#ef4444",
            timer: 20000,
            timerProgressBar: true,
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                axios
                    .delete("/api/posts/" + id)
                    .then((res) => {
                        posts.value.data = posts.value.data.filter(
                            (post) => post.id != id
                        );
                        router.push({ name: "posts.index" });
                        swal({
                            icon: "success",
                            title: "Post deleted successfully",
                        });
                    })
                    .catch((err) => {
                        swal({
                            icon: "error",
                            title: "Something went wrong",
                        });
                    });
            }
        });
    };

    return {
        posts,
        post,
        getPosts,
        storePosts,
        getPost,
        updatePost,
        deletePost,
        validationErrors,
        isFormSubmitting,
    };
}
