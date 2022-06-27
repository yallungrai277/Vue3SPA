import { ref } from "vue";

function useCategories() {
    const categories = ref([]);

    const getCategories = async () => {
        try {
            const response = await axios.get("/api/categories");
            categories.value = response.data?.data;
        } catch (err) {}
    };

    return {
        categories,
        getCategories,
    };
}

export { useCategories };
