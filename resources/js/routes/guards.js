import store from "@/store";

const authenticated = (to, from, next) => {
    const authenticated = store.getters["auth/authenticated"];
    if (authenticated) {
        next();
    } else {
        next({ name: "login" });
    }
};

const guest = (to, from, next) => {
    const authenticated = store.getters["auth/authenticated"];
    if (!authenticated) {
        next();
    } else {
        next({ name: "dashboard" });
    }
};

const isAdmin = (to, from, next) => {
    const isAdmin = store.getters["auth/isAdmin"];
    if (isAdmin) {
        next();
    } else {
        next({ name: "dashboard" });
    }
};

const isUser = (to, from, next) => {
    const isUser = store.getters["auth/isUser"];
    if (isUser) {
        next();
    } else {
        next({ name: "dashboard" });
    }
};

export { authenticated, guest, isAdmin, isUser };
