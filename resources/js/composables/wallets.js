import { ref } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

axios.defaults.baseURL = "http://127.0.0.1:8000/";

export default function useWallets() {
    const wallets = ref([]);
    const wallet = ref([]);
    const errors = ref([]);

    const getWallets = async () => {
        const response = await axios.get("api/v1/wallets");
        wallets.value = await response.data.data;
    };

    const getWallet = async (id) => {
        const response = await axios.get("api/v1/wallets/" + id);
        wallet.value = await response.data.data;
    };

    const getWalletsByUserId = async (userId) => {
        const response = await axios.get("api/v1/users/" + userId + "/wallets");
        wallets.value = await response.data.data;
        console.log(wallets.value);
    };

    const createWallet = async (data) => {
        try {
            const response = await axios.post("api/v1/wallets", data);
            router.visit(route("dashboard"));
        } catch (error) {
            if (error.response.status === 422) {
                console.log(error.response.data.errors);
                errors.value = error.response.data.errors;
            }
        }
    };

    const updateWallet = async (id) => {
        try {
            const response = await axios.put(
                "api/v1/wallets/" + id,
                wallet.value
            );
            router.visit(route("dashboard"));
        } catch (error) {
            console.log(error);
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    const destroyWallet = async (id) => {
        if (!window.confirm("Ви точно бажаєте видалити цей гаманець?")) {
            return;
        }
        try {
            const response = await axios.delete("api/v1/wallets/" + id);
            router.visit("dashboard");
        } catch (error) {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    return {
        errors,
        wallet,
        wallets,
        getWallet,
        getWallets,
        updateWallet,
        createWallet,
        destroyWallet,
        getWalletsByUserId,
    };
}
