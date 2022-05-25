import { watch } from "vue";
import { useRoute } from "vue-router";
import store from "@/store";

export default function () {
    const route = useRoute();
    watch(route, (to) => {
        store.commit("auth/REMOVE_VALIDATION_ERRORS");
    });
}
