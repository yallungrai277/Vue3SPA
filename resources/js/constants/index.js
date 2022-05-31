const ROLES = {
    ADMIN_ROLE: "admin",
    USER_ROLE: "user",
};

const ENV = {
    LOCAL: "local",
    STAGING: "staging",
    PRODUCTION: "production",
};

Object.freeze(ROLES);
Object.freeze(ENV);

export { ROLES, ENV };
