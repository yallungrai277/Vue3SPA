import _ from "lodash";
import router from "@/routes";
import { setBearerToken } from "@/helpers";
import { ROLES } from "@/constants";
import userPermission from "@/composables/userPermission";
import { setRefreshToken } from "@/helpers";
import LocalStorage from "@/helpers/localstorage";

// initial state
const state = () => ({
    authToken: null,
    refreshToken: null,
    user: null,
    validationErrors: {},
    isSubmitting: false,
    permissions: [],
});

// getters
const getters = {
    validationErrors(state) {
        return state.validationErrors;
    },
    isSubmitting(state) {
        return state.isSubmitting;
    },
    user(state) {
        return state.user;
    },
    authenticated(state) {
        if (state.authToken && state.user != null) return true;
        return false;
    },
    isAdmin(state, getters) {
        if (!getters.authenticated) return false;
        if (
            state.user.roles &&
            state.user.roles.some((role) => role.name === ROLES.ADMIN_ROLE)
        ) {
            return true;
        }
        return false;
    },
    isUser(state, getters) {
        if (!getters.authenticated) return false;
        if (
            state.user.roles &&
            state.user.roles.some((role) => role.name === ROLES.USER_ROLE)
        ) {
            return true;
        }
        return false;
    },

    permissions(state) {
        return state.permissions;
    },

    refreshToken(state) {
        return state.refreshToken;
    },
};

// actions
const actions = {
    async login({ commit, state, dispatch }, payload) {
        if (state.isSubmitting) return;

        commit("SET_SUBMITTING", true);
        commit("REMOVE_VALIDATION_ERRORS");
        try {
            const res = await axios.post("api/auth/login", payload);
            commit("SET_AUTH_TOKEN", res.data.data.auth_token);
            if (res.data.data.refresh_token) {
                commit("SET_REFRESH_TOKEN", res.data.data.refresh_token);
            }
            commit("SET_USER", res.data.data.user);
            dispatch("fetchAndSetPermissions");
            router.replace({ name: "dashboard" });
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                commit("SET_VALIDATION_ERRORS", err.response.data.errors);
            }
            throw err;
        }
        return;
    },

    async me({ commit, state, dispatch }) {
        try {
            const res = await axios.get("api/auth/me");
            commit("SET_USER", res.data.data);

            dispatch("fetchAndSetPermissions");
        } catch (err) {
            dispatch("clearAuth");
        }
        return;
    },

    async fetchAndSetPermissions({ commit }) {
        const { getUserPermission, permissions } = userPermission();
        try {
            await getUserPermission();

            commit("SET_PERMISSIONS", permissions.value);
        } catch (err) {
            console.log("some errors occured");
            // console.log(err);
        }
    },

    clearAuth({ commit }) {
        commit("SET_AUTH_TOKEN", null);
        commit("SET_REFRESH_TOKEN", null);
        commit("SET_USER", null);
    },

    removePermissions({ commit }) {
        commit("REMOVE_PERMISSIONS");
    },

    async register({ commit, state }, payload) {
        if (state.isSubmitting) return;

        commit("SET_SUBMITTING", true);
        commit("REMOVE_VALIDATION_ERRORS");
        try {
            await axios.post("api/auth/register", payload);
            swal({
                icon: "success",
                title: "Thanks for signing up. You will receive a email shortly to verify your account.",
            });
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                commit("SET_VALIDATION_ERRORS", err.response.data.errors);
            }
            throw err;
        }
        return;
    },

    async logout({ commit, state, dispatch }) {
        if (state.isSubmitting) return;
        commit("SET_SUBMITTING", true);
        try {
            await axios.post("api/auth/logout");
            commit("SET_SUBMITTING", false);
            dispatch("removePermissions");
            dispatch("clearAuth");

            router.push({ name: "login" });
        } catch (err) {
            commit("SET_SUBMITTING", false);
            swal({
                icon: "error",
                title: "Some errors occured and we could not log you out.",
            });
            throw err;
        }
        return;
    },

    async forgotPassword({ commit, state }, payload) {
        if (state.isSubmitting) return;

        commit("SET_SUBMITTING", true);
        commit("REMOVE_VALIDATION_ERRORS");
        try {
            await axios.post("api/auth/reset-password", payload);
            swal({
                icon: "success",
                title: "You will receive a password reset email. Please check your email.",
            });
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                commit("SET_VALIDATION_ERRORS", err.response.data.errors);
            } else {
                swal({
                    icon: "error",
                    title: err.response?.data?.message,
                });
            }
            throw err;
        }
        return;
    },

    async verifyEmail({ commit, state }, payload) {
        if (state.isSubmitting) return;

        commit("SET_SUBMITTING", true);
        commit("REMOVE_VALIDATION_ERRORS");
        try {
            await axios.post("api/auth/verify-email", payload);
            swal({
                icon: "success",
                title: "You will receive a new verification email link. Please check your email.",
            });
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                commit("SET_VALIDATION_ERRORS", err.response.data.errors);
            } else {
                swal({
                    icon: "error",
                    title: err.response?.data?.message,
                });
            }
            throw err;
        }
        return;
    },

    async resetPassword({ commit, state }, payload) {
        if (state.isSubmitting) return;

        commit("SET_SUBMITTING", true);
        commit("REMOVE_VALIDATION_ERRORS");
        try {
            await axios.post(`api/auth/reset-password/${payload.token}`, {
                password: payload.password,
                password_confirmation: payload.password_confirmation,
            });
            swal({
                icon: "success",
                title: "Your password has been reset. Please login with your new password.",
            });
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                commit("SET_VALIDATION_ERRORS", err.response.data.errors);
            }
            throw err;
        }
        return;
    },

    async emailVerify({ commit }, token) {
        try {
            const res = await axios.post(`api/auth/verify-email/${token}`);
            swal({
                icon: "success",
                title: "Your email has been verified. You can now login",
            });

            return res;
        } catch (err) {
            if (err?.response?.status == 422 && err?.response?.data?.errors) {
                swal({
                    icon: "error",
                    title: err.response.data.errors.token[0],
                });
            }
            throw err;
        }
    },
};

// mutations
const mutations = {
    SET_AUTH_TOKEN(state, token) {
        setBearerToken(token);
        return (state.authToken = token);
    },
    SET_REFRESH_TOKEN(state, refreshToken) {
        setRefreshToken(refreshToken);
        return (state.refreshToken = refreshToken);
    },
    SET_USER(state, user) {
        state.user = user;
    },
    REMOVE_VALIDATION_ERRORS(state) {
        state.validationErrors = {};
    },
    SET_VALIDATION_ERRORS(state, errors) {
        state.validationErrors = errors;
    },
    SET_SUBMITTING(state, boolean) {
        state.isSubmitting = boolean;
    },
    SET_PERMISSIONS(state, permissions) {
        let tempPermission = [];
        permissions.forEach((permission) => {
            tempPermission.push(permission.name);
        });
        state.permissions = tempPermission;
    },
    REMOVE_PERMISSIONS(state) {
        state.permissions = [];
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
