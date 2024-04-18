<template>
    <div
        class="p-4 rounded-lg shadow-lg flex flex-col shadow-gray-500 w-1/3 m-auto"
        :style="{
            backgroundImage: `linear-gradient(to bottom right, ${pureColor}80, #39613810)`,
        }"
    >
        <form @submit.prevent="createWallet(form)">
            <div>
                <div class="text-white flex flex-col">
                    <input
                        id="wallet_name"
                        v-model="form.wallet_name"
                        type="text"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-white"
                    />
                </div>
                <div v-if="errors.wallet_name">
                    <span class="text-sm text-red-400">{{
                        errors.wallet_name[0]
                    }}</span>
                </div>
            </div>
            <div>
                <div>
                    <select
                        class="bg-transparent border-0 text-gray-200 opacity-50 text-2xl focus:ring-0block w-full border-o focus:ring-0 uppercase font-light"
                        name=""
                        v-model="form.currency"
                        id=""
                    >
                        <option
                            class="text-black"
                            v-for="(currency, code) in currencies"
                            :key="code"
                            :value="code"
                        >
                            {{ currency.code }}
                        </option>
                    </select>
                    <div v-if="errors.currency">
                        <span class="text-sm text-red-400">{{
                            errors.currency[0]
                        }}</span>
                    </div>
                </div>
            </div>
            <div>
                <div class="flex flex-row items-center">
                    <p
                        v-if="currencies[form.currency]"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-white"
                    >
                        {{ currencies[form.currency].symbol }}
                    </p>
                    <input
                        id="balance"
                        v-model="form.balance"
                        type="number"
                        class="text-2xl bg-transparent focus:ring-0 border-0 uppercase font-black text-white"
                    />
                </div>
                <div v-if="errors.balance">
                    <span class="text-sm text-red-400">{{
                        errors.balance[0]
                    }}</span>
                </div>
            </div>
            <div>
                <div>
                    <ColorPicker
                        shape="circle"
                        picker-type="chrome"
                        disable-history
                        disable-alpha
                        format="hex6"
                        v-on:update:pureColor="changeColor($event)"
                        v-model:pureColor="pureColor"
                        class=""
                    ></ColorPicker>
                </div>
            </div>
            <button
                class="text-xl mt-8 p-2 rounded-xl shadow-xl uppercase text-white bg-pink-400"
            >
                Створити
            </button>
        </form>
    </div>
</template>

<script>
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
export default {
    components: {
        ColorPicker,
    },
};
</script>
<script setup>
import useWallets from "@/composables/wallets";
import { reactive, ref, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
const { createWallet, errors } = useWallets();
const page = usePage();
const randColor = () => {
    return "#" + Math.floor(Math.random() * 16777215).toString(16);
};
const pureColor = ref(randColor());

const changeColor = (newColor) => {
    console.log(newColor);
    form.color = newColor;
};

const form = reactive({
    user_id: page.props.auth.user.id,
    wallet_name: "назва гаманця",
    currency: "USD",
    balance: "0",
    color: pureColor.value,
});
const currencies = ref([]);
const fetchCurrencies = async () => {
    try {
        const response = await axios.get("data/currencies.json");
        currencies.value = response.data;
    } catch (error) {
        console.error("Помилка при отриманні даних з JSON:", error);
    }
};
onMounted(() => {
    fetchCurrencies();
});
</script>
