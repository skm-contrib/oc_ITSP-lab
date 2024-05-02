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
            <div v-show="isOpen" class="custom-select-container">
                <div v-for="category in categories">
                    <div
                        class="px-3 py-2 cursor-pointer hover:bg-gray-100"
                        @click="selectOption(category.name, category.id)"
                    >
                        {{ category.name }}
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        // @PropDescription
        // Всі категорії для селекту.
        categories: {
            type: Array,
            required: true,
            default: [],
        },
    },
    data() {
        return {
            isOpen: false,
            selectedOption: "",
        };
    },
    methods: {
        toggleDropdown() {
            this.isOpen = !this.isOpen;
        },
        selectOption(name, id) {
            this.selectedOption = name;
            return this.$emit("selectCategory", id);
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
