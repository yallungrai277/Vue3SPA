import router from "@/routes";
import store from "@/store";
import { setBearerToken } from "@/helpers";
import api from "@/constants/api";

let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach((prom) => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });

    failedQueue = [];
};

export default function () {
    axios.interceptors.response.use(
        function (response) {
            return response;
        },
        function (error) {
            const originalRequest = error.config;
            const refreshToken = store.getters["auth/refreshToken"];

            if (error.response.status === 401) {
                if (originalRequest._retry) {
                    store.dispatch("auth/clearAuth");
                    router.push({ name: "login" });
                    return Promise.reject(error);
                }
                if (!refreshToken) {
                    store.dispatch("auth/clearAuth");
                    router.push({ name: "login" });
                    return Promise.reject(error);
                }

                if (originalRequest.url === api.AUTH.refreshToken) {
                    store.dispatch("auth/clearAuth");
                    location.replace(window.location.origin + "/login");
                    return error;
                }

                if (isRefreshing) {
                    return new Promise(function (resolve, reject) {
                        failedQueue.push({ resolve, reject });
                    })
                        .then((token) => {
                            originalRequest.headers["Authorization"] =
                                "Bearer " + token;
                            return axios(originalRequest);
                        })
                        .catch((err) => {
                            return Promise.reject(err);
                        });
                }

                originalRequest._retry = true;
                isRefreshing = true;

                return new Promise(function (resolve, reject) {
                    setBearerToken(refreshToken);
                    axios
                        .post(api.AUTH.refreshToken)
                        .then((res) => {
                            if (!res.data) {
                                return;
                            }
                            store.commit(
                                "auth/SET_AUTH_TOKEN",
                                res.data.data.auth_token
                            );

                            store.commit(
                                "auth/SET_REFRESH_TOKEN",
                                res.data.data.refresh_token
                            );

                            originalRequest.headers["Authorization"] =
                                "Bearer " + res.data.data.auth_token;
                            processQueue(null, res.data.data.auth_token);
                            resolve(axios(originalRequest));
                        })
                        .catch((err) => {
                            processQueue(err, null);
                            reject(err);
                        })
                        .finally(() => {
                            isRefreshing = false;
                        });
                });
            } else if (error.response.status === 404) {
                router.push({ name: "404" });
                return Promise.reject(error);
            } else if (error.response.status === 500) {
                router.push({ name: "500" });
                return Promise.reject(error);
            } else if (error.response.status === 403) {
                router.push({ name: "403" });
                return Promise.reject(error);
            } else {
                return Promise.reject(error);
            }
        }
    );
}
