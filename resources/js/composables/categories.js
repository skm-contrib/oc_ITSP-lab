import { ref } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

axios.defaults.baseURL = "http://127.0.0.1:8000/";

export default function useCategories() {
    const categories = ref([]);
    const category = ref([]);
    const errors = ref([]);

    const getCategories = async () => {
        const response = await axios.get("api/v1/categories");
        categories.value = await response.data.data;
    };

    const getCategoriesByUserId = async (userId) => {
        try {
            const response = await axios.get(
                "api/v1/users/" + userId + "/categories"
            );
            categories.value = await response.data.data;
        } catch (error) {
            console.error(error);
        }
    };

    const getCategory = async (id) => {
        const response = await axios.get("api/v1/categories/" + id);
        category.value = await response.data.data;
    };

    const createCategory = async (data) => {
        try {
            const response = await axios.post("api/v1/categories", data);
            router.visit(route("dashboard"));
        } catch (error) {
            if (error.response.status === 422) {
                console.log(error.response.data.errors);
                errors.value = error.response.data.errors;
            }
        }
    };

    const updateCategory = async (id) => {
        try {
            const response = await axios.put(
                "api/v1/categories/" + id,
                category.value
            );
            router.visit(route("dashboard"));
        } catch (error) {
            console.log(error);
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    const destroyCategory = async (id) => {
        if (!window.confirm("Ви точно бажаєте видалити цю транзакцію?")) {
            return;
        }
        try {
            const response = await axios.delete("api/v1/categories/" + id);
            router.visit("dashboard");
        } catch (error) {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    return {
        errors,
        category,
        categories,
        getCategory,
        getCategories,
        updateCategory,
        createCategory,
        destroyCategory,
        getCategoriesByUserId,
    };
}
