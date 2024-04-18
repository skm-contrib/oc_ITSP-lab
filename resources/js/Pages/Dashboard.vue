<template>
    <Head title="Dashboard" />
    <AuthenticatedLayout class="m-auto">
        <div
            class="md:grid flex flex-col grid-cols-1 md:grid-cols-8 md:grid-rows-8 gap-6 mt-4 md:w-2/3 mx-4 md:mx-auto m-auto"
        >
            <div
                class="md:hidden h-fit grid grid-cols-3 overflow-hidden items-center justify-center"
            >
                <div
                    class="progress-circle bg-stone-800 rounded-full relative col-span-1 col-start-1 col-end-1"
                >
                    <svg viewBox="0 0 100 100" class="progress-ring">
                        <circle
                            class="progress-ring__circle"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="offset"
                            :r="radius"
                            cx="50"
                            cy="50"
                            :stroke-width="strokeWidth"
                        />
                    </svg>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 font-semibold text-center"
                    >
                        <div class="flex flex-col items-center">
                            <p class="text-3xl font-black text-white">
                                {{ growth_rate }}%
                            </p>
                            <p class="text-xs text-gray-400 whitespace-nowrap">
                                Темп зростання
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-3 md:row-span-2">
                <div class="flex flex-col-reverse md:flex-row h-full gap-4">
                    <div class="flex flex-col mx-2 h-full">
                        <Link class="h-full" href="/wallet-create">
                            <div class="base-btn-white h-full items-end">+</div>
                        </Link>
                        <div
                            class="bg-gray-100 mt-4 rounded-full p-3 hover:bg-gray-300 duration-300 cursor-pointer text-center items-center justify-center"
                        >
                            <img
                                src="data/images/icons/share.svg"
                                class="w-6 h-6"
                                alt=""
                            />
                        </div>
                    </div>
                    <div class="flex w-full h-full flex-col">
                        <div class="h-full">
                            <Wallet
                                :wallets="wallets"
                                :selectWallet="selectWallet"
                                :selectAllWallets="selectAllWallets"
                                :wallet_id="selectedWallet"
                            ></Wallet>
                        </div>
                    </div>
                </div>
            </div>
            <div class="md:col-span-2 row-span-2" v-if="selectedWallet">
                <div class="flex flex-row gap-4 h-full w-full overflow-hidden">
                    <div class="flex flex-col h-full w-full">
                        <TransactionActivity
                            @growth_rate="updateGrowthRate"
                            :wallet_id="selectedWallet"
                            :selectedWalletCurrency="selectedWalletCurrency"
                        ></TransactionActivity>
                    </div>
                </div>
            </div>

            <div
                class="md:col-span-3 md:col-start-6 row-span-4 rounded-3xl bg-white"
            >
                <Transactions
                    :wallet_id="selectedWallet"
                    :selectedWalletCurrency="selectedWalletCurrency"
                />
            </div>

            <div
                class="bg-stone-800 hidden md:block rounded-full w-fit overflow-auto items-center justify-center row-span-1 col-span-1 col-start-1 row-start-3"
            >
                <div class="progress-circle relative">
                    <svg viewBox="0 0 100 100" class="progress-ring">
                        <circle
                            class="progress-ring__circle"
                            :stroke-dasharray="circumference"
                            :stroke-dashoffset="offset"
                            :r="radius"
                            cx="50"
                            cy="50"
                            :stroke-width="strokeWidth"
                        />
                    </svg>
                    <div
                        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 font-semibold text-center"
                    >
                        <div class="flex flex-col items-center">
                            <p class="text-3xl font-black text-white">
                                {{ growth_rate }}%
                            </p>
                            <p class="text-xs text-gray-400 whitespace-nowrap">
                                Темп зростання
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import { computed } from "vue";
import Wallet from "./Profile/Partials/Wallet.vue";
import Transactions from "@/Pages/Models/Transaction/TransactionIndex.vue";
import TransactionActivity from "@/Pages/Models/Transaction/TransactionActivity.vue";

export default {
    data() {},
    components: {
        Wallet,
        Transactions,
        TransactionActivity,
        Link,
    },
    methods: {},
};
</script>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import useWallets from "@/composables/wallets";
import { usePage } from "@inertiajs/inertia-vue3";

import { Head } from "@inertiajs/vue3";
import { onMounted, ref, useAttrs } from "vue";
const selectedWalletCurrency = ref(null);
const growth_rate = ref(0);

const radius = 40; // Радіус кругового прогрес бару
const strokeWidth = 8; // Ширина лінії прогрес бару
//Обчислюємо довжину кола (необхідно для обчислення довжини дуги)
const circumference = 2 * Math.PI * radius;
const offset = computed(() => {
    const progressOffset = ref(0);
    if (growth_rate.value > 100) {
        return (progressOffset.value = 100);
    } else {
        progressOffset.value =
            ((100 - growth_rate.value) / 100) * circumference;
    }
    return progressOffset.value;
});

function calculateOffset() {}

const attrs = useAttrs();
const { wallets, getWalletsByUserId } = useWallets();

const selectedWallet = ref(null);

const selectWallet = (id) => {
    selectedWallet.value = id;
    wallets.value.forEach((wallet) => {
        if (wallet.id === id) {
            selectedWalletCurrency.value = wallet.currency;
        }
    });
};
const selectAllWallets = () => {
    console.log("all");
};

const selectFirstWallet = () => {
    if (wallets.value.length > 0) {
        selectedWallet.value = wallets.value[0].id;
        selectWallet(wallets.value[0].id);
    }
};

const updateGrowthRate = (rate) => {
    growth_rate.value = rate;
};

onMounted(async () => {
    await getWalletsByUserId(attrs.auth.user.id);
    selectFirstWallet();
    calculateOffset();
});
</script>

<style>
.transition-absolute {
    transition: all 0.3s ease;
}

.transition-relative {
    transition: all 0.3s ease;
}
.progress-circle {
    position: relative;
    width: 100%;
    height: 100%;
}

.progress-ring {
    width: 100%;
    height: 100%;
}

.progress-ring__circle {
    fill: none;
    stroke: #e0644a; /* Колір прогрес бару */
    stroke-linecap: round;
    transition: stroke-dashoffset 0.3s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 16px;
    font-weight: bold;
}
</style>
