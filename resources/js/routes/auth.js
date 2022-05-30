import Guest from "@/layouts/Guest.vue";
import Authenticated from "@/layouts/Authenticated.vue";
import { authenticated, guest } from "./guards";

import Login from "@/pages/auth/Login.vue";
import Register from "@/pages/auth/Register.vue";
import VerifyEmail from "@/pages/auth/VerifyEmail.vue";
import ResetPassword from "@/pages/auth/ResetPassword";
import EmailVerify from "@/pages/auth/EmailVerify";
import Dashboard from "@/pages/auth/Dashboard";
import ForgotPassword from "@/pages/auth/ForgotPassword.vue";

export default [
    {
        path: "/login",
        component: Login,
        name: "login",
        meta: {
            layout: Guest,
            title: "Login",
        },
        beforeEnter: [guest],
    },
    {
        path: "/signup",
        component: Register,
        name: "register",
        meta: {
            layout: Guest,
            title: "Sign Up",
        },
        beforeEnter: [guest],
    },
    {
        path: "/forgot-passord",
        component: ForgotPassword,
        name: "forgot.password",
        meta: {
            layout: Guest,
            title: "Forgot Password",
        },
        beforeEnter: [guest],
    },
    {
        path: "/verify-email",
        component: VerifyEmail,
        name: "verify.email",
        meta: {
            layout: Guest,
            title: "Verify Email",
        },
        beforeEnter: [guest],
    },
    {
        path: "/verify-email/:token",
        component: EmailVerify,
        name: "email.verify",
        meta: {
            layout: Guest,
            title: "Verify Email",
        },
        beforeEnter: [guest],
    },
    {
        path: "/reset-password/:token",
        component: ResetPassword,
        name: "reset.password",
        meta: {
            layout: Guest,
            title: "Reset Password",
        },
        beforeEnter: [guest],
    },
    {
        path: "/dashboard",
        component: Dashboard,
        name: "dashboard",
        meta: {
            layout: Authenticated,
            title: "Dashboard",
        },
        beforeEnter: [authenticated],
    },
];
