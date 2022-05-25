import axios from "axios";
import { ref } from "vue";

function useCategories() {
    const categories = ref([]);

    const getCategories = async () => {
        axios.get("/api/categories").then((response) => {
            categories.value = response.data.data;
        });
    };

    return {
        categories,
        getCategories,
    };
}

export { useCategories };
