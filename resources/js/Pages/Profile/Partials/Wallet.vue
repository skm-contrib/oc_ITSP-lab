<template>
    <div
        v-if="wallet_id"
        class="w-full h-full border-2 bg-gray-100 rounded-3xl"
        :style="{
            //backgroundImage: `linear-gradient(to bottom right, ${wallet.color}80, #39613810)`,
        }"
    >
        <div class="flex flex-col p-4 bg-white rounded-3xl">
            <div class="flex flex-row justify-between items-center">
                <h2 class="text-xl uppercase font-black">
                    {{ wallet.wallet_name }}
                </h2>
                <PartSelect
                    :selectWallet="selectWallet"
                    :selectAllWallets="selectAllWallets"
                    :wallets="wallets"
                ></PartSelect>
            </div>
            <h2 class="text-2xl uppercase font-light text-black opacity-50">
                {{ wallet.currency }}
            </h2>
            <div
                class="text-4xl pt-4 flex flex-row uppercase font-black text-black"
            >
                <div v-if="currencies[wallet.currency]">
                    <p class="font-medium">
                        {{ currencies[wallet.currency].symbol }}
                    </p>
                </div>
                <p>{{ wallet.balance }}</p>
            </div>
            <div class="flex flex-row pt-4 w-full gap-4">
                <div class="base-btn-colored">
                    <button
                        @click="
                            (openedAddingTransaction = true), (receive = true)
                        "
                    >
                        Отримав
                    </button>
                </div>
                <div class="base-btn">
                    <button
                        @click="
                            (openedAddingTransaction = true), (receive = false)
                        "
                    >
                        Відправив
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-gray-100 rounded-b-3xl">
            <div class="flex flex-row justify-between p-4 items-center">
                <div class="flex-col">
                    <p class="text-gray-400 text-xs">
                        Маржа за останні 30 днів
                    </p>
                    <div class="flex flex-row">
                        <div
                            class="text-2xl font-bold flex items-center gap-1 flex-row"
                        >
                            <div v-if="currencies[wallet.currency]">
                                <p class="font-black text-green-500 text-3xl">
                                    {{ currencies[wallet.currency].symbol }}
                                </p>
                            </div>
                            <p class="">0.00</p>
                        </div>
                    </div>
                </div>
                <div
                    class="text-xs text-[#e0644a] font-bold flex flex-row items-center gap-2 hover:text-[#c95a42] cursor-pointer group"
                    v-if="wallet.id"
                >
                    <svg class="w-6 h-6" viewBox="0 0 24 24">
                        viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M1.25 12C1.25 6.06294 6.06294 1.25 12 1.25C12.4142 1.25 12.75 1.58579 12.75 2C12.75 2.41421 12.4142 2.75 12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 11.5858 21.5858 11.25 22 11.25C22.4142 11.25 22.75 11.5858 22.75 12C22.75 17.9371 17.9371 22.75 12 22.75C6.06294 22.75 1.25 17.9371 1.25 12ZM16.7705 2.27591C18.1384 0.908028 20.3562 0.908028 21.7241 2.27591C23.092 3.6438 23.092 5.86158 21.7241 7.22947L15.076 13.8776C14.7047 14.2489 14.4721 14.4815 14.2126 14.684C13.9069 14.9224 13.5761 15.1268 13.2261 15.2936C12.929 15.4352 12.6169 15.5392 12.1188 15.7052L9.21426 16.6734C8.67801 16.8521 8.0868 16.7126 7.68711 16.3129C7.28742 15.9132 7.14785 15.322 7.3266 14.7857L8.29477 11.8812C8.46079 11.3831 8.56479 11.071 8.7064 10.7739C8.87319 10.4239 9.07761 10.0931 9.31605 9.78742C9.51849 9.52787 9.7511 9.29529 10.1224 8.924L16.7705 2.27591ZM20.6634 3.33657C19.8813 2.55448 18.6133 2.55448 17.8312 3.33657L17.4546 3.7132C17.4773 3.80906 17.509 3.92327 17.5532 4.05066C17.6965 4.46372 17.9677 5.00771 18.48 5.51999C18.9923 6.03227 19.5363 6.30346 19.9493 6.44677C20.0767 6.49097 20.1909 6.52273 20.2868 6.54543L20.6634 6.16881C21.4455 5.38671 21.4455 4.11867 20.6634 3.33657ZM19.1051 7.72709C18.5892 7.50519 17.9882 7.14946 17.4193 6.58065C16.8505 6.01185 16.4948 5.41082 16.2729 4.89486L11.2175 9.95026C10.801 10.3668 10.6376 10.532 10.4988 10.7099C10.3274 10.9297 10.1804 11.1676 10.0605 11.4192C9.96337 11.623 9.88868 11.8429 9.7024 12.4017L9.27051 13.6974L10.3026 14.7295L11.5983 14.2976C12.1571 14.1113 12.377 14.0366 12.5808 13.9395C12.8324 13.8196 13.0703 13.6726 13.2901 13.5012C13.468 13.3624 13.6332 13.199 14.0497 12.7825L19.1051 7.72709Z"
                            fill="#e0644a"
                        />
                    </svg>
                    <Link
                        :href="
                            route('wallet.edit', {
                                wallet_id: wallet.id,
                            })
                        "
                    >
                        Редагувати<br />інформацію про гаманець</Link
                    >
                </div>
            </div>
        </div>
    </div>

    <div
        v-show="openedAddingTransaction"
        class="fixed z-10 top-0 left-0 w-full h-full justify-center items-center flex"
    >
        <div
            @click="openedAddingTransaction = false"
            class="bg-black fixed top-0 left-0 w-full h-full opacity-25"
        ></div>
        <div class="z-20 bg-white p-4 rounded-3xl">
            <TransactionCreate
                :receive="receive"
                @close="openedAddingTransaction = false"
                :openedAddingTransaction="openedAddingTransaction"
                :wallet_id="wallet_id"
                >asd</TransactionCreate
            >
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import PartSelect from "@/Components/finComponents/PartSelect.vue";
import TransactionCreate from "@/Pages/Models/Transaction/TransactionCreate.vue";

export default {
    components: {
        Link,
        TransactionCreate,
        PartSelect,
    },
};
</script>

<script setup>
import axios from "axios";
import useWallets from "@/composables/wallets";

import { onMounted, ref, watch } from "vue";
const { wallet, destroyWallet, getWallet } = useWallets();

const currencies = ref([]);
const openedAddingTransaction = ref(false);
const receive = ref(false);
const props = defineProps({
    wallet_id: Number,
    wallets: Array,
    selectWallet: Function,
    selectAllWallets: Function,
});
watch(
    () => props.wallet_id,
    (newValue, oldValue) => {
        getWallet(newValue);
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
});
</script>
