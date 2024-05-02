<template>
    <div class="flex flex-col h-[664px] w-full bg-gray-100 rounded-3xl">
        <p class="m-4 font-black text-2xl">Історія транзакцій</p>
        <div class="flex flex-row w-full p-4">
            <img
                src="data/images/icons/search.svg"
                class="w-12 border-2 p-3 rounded-full"
                alt=""
            />
            <input
                type="text"
                v-model="searchTerm"
                class="my-input w-full ml-4"
                placeholder="Пошук в активності.."
                @change="searchTransactions(wallet_id, searchTerm)"
            />
        </div>
        <div v-if="wallet_id" class="w-full h-full overflow-hidden">
            <div
                v-if="transactions"
                class="border-2 w-full overflow-y-scroll overflow-x-hidden rounded-3xl border-gray-100 h-full bg-white"
            >
                <div
                    class="overflow-hidden rounded-3xl duration-300 cursor-pointer hover:border-gray-200 border-2 border-transparent hover:bg-gray-100 w-full p-4 text-center"
                    v-for="(transaction_date, index) in transactions"
                    :key="index"
                >
                    <p class="font-light text-stone-400 text-sm">
                        {{
                            new Date(transaction_date.date).toLocaleDateString(
                                "uk-UA",
                                {
                                    year: "numeric",
                                    month: "long",
                                    day: "numeric",
                                }
                            )
                        }}
                    </p>

                    <div
                        v-for="transaction in transaction_date.transactions"
                        class="flex flex-row justify-between p-2"
                    >
                        <div class="flex flex-row">
                            <div
                                v-if="transaction.transaction_type === 'income'"
                                class="text-green-500 font-light gap-1 flex flex-row text-xl"
                            >
                                {{ transaction.amount }}
                                <div v-if="currencies[selectedWalletCurrency]">
                                    <p class="font-black">
                                        {{
                                            currencies[selectedWalletCurrency]
                                                .symbol
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div
                                v-else-if="
                                    transaction.transaction_type === 'expense'
                                "
                                class="text-stone-800 font-light gap-1 flex flex-row text-xl"
                            >
                                -{{ transaction.amount }}
                                <div v-if="currencies[selectedWalletCurrency]">
                                    <p class="font-black">
                                        {{
                                            currencies[selectedWalletCurrency]
                                                .symbol
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col text-end">
                            <div>{{ transaction.description }}</div>
                            <div class="text-xs text-stone-400">
                                {{ transaction.category.name }}
                            </div>
                        </div>
                    </div>
                    <!-- <div class="flex flex-col items-start">
                        <p class="font-bold text-xl">
                            {{ transaction.category.name }}
                        </p>
                        <p class="text-gray-400 text-xs">
                            {{ transaction.description }}
                        </p>
                    </div>

                    <p class="text-green-500">
                        <button @click="editTransaction(transaction.id)">
                            Редагувати
                        </button>
                    </p>
                    <p class="text-red-500">
                        <button @click="destroyTransaction(transaction.id)">
                            Видалити
                        </button>
                    </p>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TransactionCreate from "./TransactionCreate.vue";

export default {
    components: {
        TransactionCreate,
    },
    // @PropDescription
    // Ідентифікатор гаманця для подальшого отримання його транзакцій.
    props: {
        // @PropDescription
        // Ідентифікатор гаманця для подальшого отримання його транзакцій.
        wallet_id: {
            type: Number,
            required: true,
            default: "0",
        },
    },
    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            const formattedDate = `${date.getDate()}.${
                date.getMonth() + 1
            }.${date.getFullYear()}`;
            return formattedDate;
        },
    },
};
</script>

<script setup>
import axios from "axios";
import useTransactions from "@/composables/transactions";
import { defineProps, watch, ref, onMounted } from "vue";

const {
    getTransactionsByWalletId,
    destroyTransaction,
    transactions,
    searchTransactions,
} = useTransactions();
const currencies = ref([]);

const props = defineProps({
    wallet_id: Number,
    selectedWalletCurrency: String,
});
watch(
    () => props.wallet_id,
    (newValue, oldValue) => {
        if (newValue) {
            getTransactionsByWalletId(newValue);
        }
    }
);

const searchTerm = ref("");

const fetchCurrencies = async () => {
    try {
        const response = await axios.get("data/currencies.json");
        currencies.value = response.data;
    } catch (error) {
        console.error("Помилка при отриманні даних з JSON:", error);
    }
};

onMounted(async () => {
    fetchCurrencies();
});
</script>
