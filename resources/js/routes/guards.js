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
        next({ name: "posts.index" });
    }
};

export { authenticated, guest };
