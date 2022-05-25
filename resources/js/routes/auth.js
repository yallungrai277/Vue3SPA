import Login from "@/pages/auth/Login.vue";
import Register from "@/pages/auth/Register.vue";
import VerifyEmail from "@/pages/auth/VerifyEmail.vue";
import ResetPassword from "@/pages/auth/ResetPassword";
import EmailVerify from "@/pages/auth/EmailVerify";
import ForgotPassword from "@/pages/auth/ForgotPassword.vue";

import Guest from "@/layouts/Guest.vue";

export default [
    {
        path: "/login",
        component: Login,
        name: "login",
        meta: {
            layout: Guest,
            title: "Login",
        },
    },
    {
        path: "/signup",
        component: Register,
        name: "register",
        meta: {
            layout: Guest,
            title: "Sign Up",
        },
    },
    {
        path: "/forgot-passord",
        component: ForgotPassword,
        name: "forgot.password",
        meta: {
            layout: Guest,
            title: "Forgot Password",
        },
    },
    {
        path: "/verify-email",
        component: VerifyEmail,
        name: "verify.email",
        meta: {
            layout: Guest,
            title: "Verify Email",
        },
    },
    {
        path: "/verify-email/:token",
        component: EmailVerify,
        name: "email.verify",
        meta: {
            layout: Guest,
            title: "Verify Email",
        },
    },
    {
        path: "/reset-password/:token",
        component: ResetPassword,
        name: "reset.password",
        meta: {
            layout: Guest,
            title: "Reset Password",
        },
    },
];
