<template>
    <div class="relative" @click="toggleDropdown">
        <div
            class="border my-select text-sm p-2 gap-2 flex items-center justify-between"
        >
            <span>Всі гаманці</span>
            <svg
                class="w-4 h-4 duration-200"
                :class="{ 'transform rotate-180': isOpen }"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                ></path>
            </svg>
        </div>
        <transition name="fade">
            <div v-show="isOpen" class="custom-select-container z-10">
                <div
                    v-for="(wallet, index) in wallets"
                    :key="wallet.id"
                    @click="selectOption(wallet.id)"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase"
                >
                    {{ wallet.wallet_name }}
                </div>
                <div
                    @click="selectOption('all')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase"
                >
                    Всі гаманці
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        // @PropDescription
        // Ідентифікатори гаманців.
        wallets: {
            type: Array,
            required: true,
        },
        // @PropDescription
        // Обраний гаманець.
        selectWallet: {
            type: Function,
            required: true,
        },
        // @PropDescription
        // Вибрати всі гаманці.
        selectAllWallets: {
            type: Function,
            required: true,
        },
    },
    data() {
        return {
            isOpen: false,
            selectedOption: "Оберіть гаманець",
        };
    },
    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        selectOption(event) {
            if (event != "all") {
                this.isOpen == false;
                return this.selectWallet(event);
            } else {
                this.isOpen == false;
                return this.selectAllWallets();
            }
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
