<template>
    <div
        class="cursor-pointer bg-gray-100 rounded-3xl flex-row flex items-center p-2 hover:bg-gray-300 duration-300"
        @click="$emit('close')"
    >
        <img src="data/images/icons/cross.svg" class="w-8" alt="" />
        <div class="w-full text-end font-bold">
            <p v-if="receive == true">ОТРИМАВ КОШТИ</p>
            <p v-else-if="receive == false">ВІДПРАВИВ КОШТИ</p>
        </div>
    </div>
    <div class="flex flex-col text-stone-800">
        <form @submit.prevent="createTransaction(form)">
            <div>
                <div
                    class="text-stone-800 border-b-2 flex flex-row items-center"
                >
                    <div>
                        <p>Сума</p>
                    </div>
                    <input
                        id="amount"
                        v-model="form.amount"
                        type="number"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-stone-800"
                    />
                </div>
                <div v-if="errors.amount">
                    <span class="text-sm text-red-400">{{
                        errors.amount[0]
                    }}</span>
                </div>
            </div>
            <div>
                <div v-if="errors.transaction_type">
                    <span class="text-sm text-red-400">{{
                        errors.transaction_type[0]
                    }}</span>
                </div>
            </div>
            <div>
                <div
                    class="text-stone-800 border-b-2 flex flex-row items-center"
                >
                    <CategorySelect
                        @selectCategory="handleCategorySelection"
                        :categories="categories"
                    ></CategorySelect>
                    <div v-if="errors.expense_category">
                        <span class="text-sm text-red-400">{{
                            errors.expense_category[0]
                        }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div
                    class="text-stone-800 border-b-2 flex flex-row items-center"
                >
                    <div>
                        <p>Опис</p>
                    </div>
                    <input
                        id="description"
                        v-model="form.description"
                        type="text"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-stone-800"
                    />
                </div>
                <div v-if="errors.description">
                    <span class="text-sm text-red-400">{{
                        errors.description[0]
                    }}</span>
                </div>
            </div>
            <div>
                <div
                    class="text-stone-800 border-b-2 flex flex-row items-center"
                >
                    <div>
                        <p>Дата</p>
                    </div>
                    <input
                        id="transaction_date"
                        v-model="form.transaction_date"
                        type="date"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-stone-800"
                    />
                </div>
                <div v-if="errors.transaction_date">
                    <span class="text-sm text-red-400">{{
                        errors.transaction_date[0]
                    }}</span>
                </div>
            </div>
            <button
                class="text-3xl uppercase text-stone-500 hover:text-stone-800 duration-300"
            >
                +
            </button>
        </form>
    </div>
</template>

<script setup>
import useCategory from "@/composables/categories";
import useTransaction from "@/composables/transactions";
import CategorySelect from "@/Components/finComponents/CategorySelect.vue";
import { reactive, ref, watch, onMounted, defineProps } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const { createTransaction, errors } = useTransaction();
const { getCategories, categories } = useCategory();

const page = usePage();

const props = defineProps({
    wallet_id: Number,
    receive: Boolean,
    openedAddingTransaction: Boolean,
});

const form = reactive({
    user_id: page.props.auth.user.id,
    wallet_id: props.wallet_id,
    amount: 0,
    transaction_type: null,
    category_id: null,
    description: "",
    transaction_date: new Date().toISOString().slice(0, 10),
});
const handleCategorySelection = (id) => {
    form.category_id = id;
};
const currencies = ref([]);
const fetchCurrencies = async () => {
    try {
        const response = await axios.get("data/currencies.json");
        currencies.value = response.data;
    } catch (error) {
        console.error("Помилка при отриманні даних з JSON:", error);
    }
};
watch(
    [() => props.wallet_id, () => props.receive],
    ([newWalletId, newReceive], [oldWalletId, oldReceive]) => {
        form.wallet_id = newWalletId;
        form.transaction_type = newReceive ? "income" : "expense";
    }
);
onMounted(async () => {
    await getCategories();
    fetchCurrencies();

    if (props.receive) {
        form.transaction_type = "income";
    } else {
        form.transaction_type = "expense";
    }
});
</script>
