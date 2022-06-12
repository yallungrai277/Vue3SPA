const ROLES = {
    ADMIN_ROLE: "admin",
    USER_ROLE: "user",
};

const ENV = {
    LOCAL: "local",
    STAGING: "staging",
    PRODUCTION: "production",
};

const PERMISSIONS = {
    POSTS_MANAGE: "posts.manage",
    POSTS_CREATE: "posts.create",
    POSTS_UPDATE: "posts.update",
    POSTS_DELETE: "posts.delete",
};

Object.freeze(ROLES);
Object.freeze(ENV);
Object.freeze(PERMISSIONS);

export { ROLES, PERMISSIONS, ENV };
