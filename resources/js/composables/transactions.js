import { ref } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

axios.defaults.baseURL = "http://127.0.0.1:8000/";

export default function useTransactions() {
    const transactions = ref([]);
    const transaction = ref([]);
    const errors = ref([]);

    const totalExpences = ref([]);
    const totalIncome = ref([]);

    const getTransactions = async () => {
        const response = await axios.get("api/v1/transactions");
        transactions.value = await response.data.data;
    };

    const getTransactionsByWalletId = async (walletId) => {
        try {
            const response = await axios.get(
                "api/v1/wallets/" + walletId + "/transactions"
            );
            transactions.value = await response.data;
            console.log("tw" + transactions.value);
        } catch (error) {
            console.error(error);
        }
    };

    const getTransaction = async (id) => {
        const response = await axios.get("api/v1/transactions/" + id);
        transaction.value = await response.data.data;
    };

    const getTotalExpences = async (wallet_id, period) => {
        try {
            const response = await axios.get(
                `api/v1/wallets/${wallet_id}/transactions/expenses?period=${period}`
            );
            totalExpences.value = response.data;
        } catch (error) {
            console.error("Error fetching expenses:", error);
        }
    };

    const getTotalIncomes = async (wallet_id, period) => {
        try {
            const response = await axios.get(
                `api/v1/wallets/${wallet_id}/transactions/incomes?period=${period}`
            );
            totalIncome.value = response.data;
        } catch (error) {
            console.error("Error fetching expenses:", error);
        }
    };

    const searchTransactions = async (wallet_id, search) => {
        if (search === "") {
            getTransactionsByWalletId(wallet_id);
            return;
        }
        try {
            const response = await axios.get(
                `api/v1/wallets/${wallet_id}/transactions/search?search=${search}`
            );
            transactions.value = response.data.data;
        } catch (error) {
            console.error("Error fetching expenses:", error);
        }
    };

    const createTransaction = async (data) => {
        try {
            const response = await axios.post("api/v1/transactions", data);
            router.visit(route("dashboard"));
        } catch (error) {
            if (error.response.status === 422) {
                console.log(error.response.data.errors);
                errors.value = error.response.data.errors;
            } else if (error.response.status === 400) {
                console.log(error.response.status);
                window.alert(
                    "Ви не можете додати транзакцію бо її сума перевищує баланс вашого гаманця!"
                );
            }
        }
    };

    const updateTransaction = async (id) => {
        try {
            const response = await axios.put(
                "api/v1/transactions/" + id,
                transaction.value
            );
            router.visit(route("dashboard"));
        } catch (error) {
            console.log(error);
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    const destroyTransaction = async (id) => {
        if (!window.confirm("Ви точно бажаєте видалити цю транзакцію?")) {
            return;
        }
        try {
            const response = await axios.delete("api/v1/transactions/" + id);
            router.visit("dashboard");
        } catch (error) {
            if (error.response.status === 422) {
                errors.value = error.response.data.errors;
            }
        }
    };

    return {
        errors,
        transaction,
        transactions,
        getTransaction,
        getTransactions,
        updateTransaction,
        createTransaction,
        destroyTransaction,
        getTransactionsByWalletId,
        getTotalExpences,
        getTotalIncomes,
        totalExpences,
        totalIncome,
        searchTransactions,
    };
}
