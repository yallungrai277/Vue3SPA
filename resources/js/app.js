require("./bootstrap");

import { createApp } from "vue";

import App from "./App.vue";
import router from "./routes";
import store from "./store";
import LocalStorage from "@/helpers/localstorage";

// Pagination
import LaravelVuePagination from "laravel-vue-pagination";
// Sweet Alert
import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import axiosInterceptors from "@/helpers/axiosInterceptors";
import { ENV } from "@/constants";

if (process.env.MIX_ENV != ENV.LOCAL) {
    axiosInterceptors();
}

store
    .dispatch("auth/me", new LocalStorage().getItem("authToken"))
    .finally(() => {
        const app = createApp(App);
        app.use(router);
        app.use(store);
        app.use(VueSweetalert2);
        window.swal = app.config.globalProperties.$swal;
        app.component("Pagination", LaravelVuePagination);
        app.mount("#app");
    });
