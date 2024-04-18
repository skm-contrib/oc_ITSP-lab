<template>
    <div class="relative w-32" @click="toggleDropdown">
        <div
            class="border my-select text-sm p-2 gap-2 flex items-center justify-between"
        >
            <span>{{ selectedOption }}</span>
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
                    @click="selectOption('day')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase z-10"
                >
                    День
                </div>
                <div
                    @click="selectOption('week')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase z-10"
                >
                    Тиждень
                </div>
                <div
                    @click="selectOption('month')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase z-10"
                >
                    Місяць
                </div>
                <div
                    @click="selectOption('half_year')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase z-10"
                >
                    Пів року
                </div>
                <div
                    @click="selectOption('year')"
                    class="px-3 py-2 cursor-pointer hover:bg-gray-100 uppercase z-10"
                >
                    Рік
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        changeDate: {
            type: Function,
            required: true,
        },
    },
    data() {
        return {
            isOpen: false,
            selectedOption: "День",
        };
    },
    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        selectOption(event) {
            switch (event) {
                case "day":
                    this.selectedOption = "День";
                    break;
                case "week":
                    this.selectedOption = "Тиждень";
                    break;
                case "month":
                    this.selectedOption = "Місяць";
                    break;
                case "half_year":
                    this.selectedOption = "Пів року";
                    break;
                case "year":
                    this.selectedOption = "Рік";
                    break;
            }
            this.isOpen == false;
            return this.changeDate(event);
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
