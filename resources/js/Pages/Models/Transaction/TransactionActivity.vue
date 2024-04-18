<template>
    <div class="w-full h-full bg-gray-100 border-2 rounded-3xl">
        <div
            class="bg-white p-4 rounded-3xl h-1/2 flex flex-col justify-between"
        >
            <div class="flex flex-row justify-between items-center">
                <div>
                    <img
                        src="data/images/icons/up.svg"
                        class="w-6 h-6 rotate-180"
                        alt=""
                    />
                </div>
                <div>
                    <DateSelect
                        :selectedOptionProp="changeSelectedIncomeOption"
                        :changeDate="changeDateIncome"
                    ></DateSelect>
                </div>
            </div>

            <div class="flex flex-row justify-between items-center">
                <div class="flex flex-col justify-end align-bottom">
                    <p class="text-sm text-gray-400">Загальний прибуток</p>
                    <div
                        class="text-2xl font-bold flex items-center gap-1 flex-row"
                    >
                        <div v-if="currencies[selectedWalletCurrency]">
                            <p class="font-black text-green-500 text-3xl">
                                {{ currencies[selectedWalletCurrency].symbol }}
                            </p>
                        </div>
                        <p>{{ totalIncome.total_income }}</p>
                    </div>
                </div>
                <div class="flex flex-col text-sm text-gray-400">
                    <p>
                        <span>{{ totalIncome.start_date }}</span> -

                        <span>{{ totalIncome.end_date }}</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="p-4 flex flex-col justify-between">
            <div class="flex flex-row justify-between items-center">
                <div>
                    <img
                        src="data/images/icons/down.svg"
                        class="w-6 h-6"
                        alt=""
                    />
                </div>
                <div>
                    <DateSelect
                        :selectedOptionProp="changeSelectedExpenseOption"
                        :changeDate="changeDateExpense"
                    ></DateSelect>
                </div>
            </div>

            <div class="flex flex-row justify-between items-center mt-6">
                <div class="flex flex-col justify-end align-bottom">
                    <p class="text-sm text-gray-400">Загальні витрати</p>
                    <div
                        class="text-2xl font-bold flex items-center gap-1 flex-row"
                    >
                        <div v-if="currencies[selectedWalletCurrency]">
                            <p class="font-black text-[#e0644a] text-3xl">
                                {{ currencies[selectedWalletCurrency].symbol }}
                            </p>
                        </div>
                        <p>{{ totalExpences.total_expense }}</p>
                    </div>
                </div>
                <div class="flex flex-col text-sm text-gray-400">
                    <p>
                        <span>{{ totalExpences.start_date }}</span> -

                        <span>{{ totalExpences.end_date }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DateSelect from "@/Components/finComponents/DateSelect.vue";
export default {
    components: {
        DateSelect,
    },
};
</script>

<script setup>
import { reactive, ref, onMounted, defineProps, watch } from "vue";
import useTransactions from "@/composables/transactions";
import { defineEmits } from "vue";
const selectedIncomeOption = ref("day");
const selectedExpenseOption = ref("day");

const emit = defineEmits(["growth_rate"]);

const {
    transactions,
    getTransactions,
    getTotalExpences,
    getTotalIncomes,
    totalExpences,
    totalIncome,
} = useTransactions();
const currencies = ref([]);

const props = defineProps({
    wallet_id: Number,
    selectedWalletCurrency: String,
});
const growth_rate = ref(0);
const changeDateExpense = async (event) => {
    (await selectedExpenseOption.value) == event;
    await getTotalExpences(props.wallet_id, event);
    calculateGrowthRate();
};
const changeDateIncome = async (event) => {
    (await selectedIncomeOption.value) == event;
    await getTotalIncomes(props.wallet_id, event);
    calculateGrowthRate();
};
function calculateGrowthRate() {
    const growthRate = (
        (totalIncome.value.total_income / totalExpences.value.total_expense) *
        100
    ).toFixed(2);
    emit("growth_rate", growthRate);
}

const getTotalIncome = async (wallet_id, date) => {
    await getTotalIncomes(wallet_id, date);
    calculateGrowthRate();
};

const getTotalExpence = async (wallet_id, date) => {
    await getTotalExpences(wallet_id, date);
    calculateGrowthRate();
};

watch(
    () => props.wallet_id,
    (newValue, oldValue) => {
        getTotalIncome(newValue, selectedIncomeOption.value);
        getTotalExpence(newValue, selectedExpenseOption.value);
    }
);

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
    await getTotalExpences(props.wallet_id, "day");
    await getTotalIncomes(props.wallet_id, "day");
    calculateGrowthRate();
});
</script>
