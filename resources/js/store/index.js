import { createStore, createLogger } from "vuex";
import auth from "@/store/modules/auth";
import { ENV } from "@/constants";

const isLocal = process.env.MIX_ENV === ENV.LOCAL;

const logger = createLogger({
    collapsed: false, // auto-expand logged mutation
    logActions: isLocal, // Log Actions
    logMutations: isLocal, // Log mutations
});
// Create a new store instance.
export default createStore({
    modules: {
        auth,
    },
    plugins: [logger],
});
