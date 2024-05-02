<template>
    <div>
        <Link href="wallets">Гаманці</Link>
        <a href="/">На головну</a>
        <form @submit.prevent="handleSubmit" @if="form">
            <input id="file-input" type="file" @change="handleFileChange" />
            <div v-if="sumFound">
                <div class="w-2/3 grid grid-flow-col">
                    <div
                        v-for="(sumchar, index) in foundSum.split('')"
                        :key="index"
                    >
                        <div v-if="sumchar == '.' || sumchar == ','">.</div>
                        <VueScrollPicker
                            @update:modelValue="updateSum(index, $event)"
                            v-else
                            v-model="scrollPickerValues[index]"
                            :options="generateOptions(index)"
                        />
                    </div>
                </div>

                <h3 class="bg-green-200 text-xl font-medium">Сума:</h3>
                <div class="w-1/3 m-auto text-center font-bold">
                    <div>{{ foundSum }}</div>
                    <div>{{ updatedSum }}</div>
                </div>
            </div>
            <div v-if="category">
                <h3 class="bg-green-200 text-xl font-medium">Категорія:</h3>
                <div class="w-1/3 m-auto text-center font-bold">
                    {{ category }}
                </div>
            </div>
            <div v-if="text">
                <h3 class="bg-green-200 text-xl font-medium">
                    Конвертований текст:
                </h3>
                <div class="w-1/3 m-auto">{{ text }}</div>
            </div>
            <div class="images-container pt-12">
                <div class="image">
                    <h3>Необроблене фото:</h3>
                    <img
                        alt="Необроблене фото"
                        :src="originalImage"
                        width="400"
                    />
                </div>
                {{ error }}
                <div class="image">
                    <h3>Оброблене фото:</h3>
                    <img
                        alt="Оброблене фото"
                        :src="processedImage"
                        width="400"
                    />
                </div>
            </div>
        </form>
    </div>
</template>
<script>
// @ComponentDescription
// Головний компонент для управління гаманцями та обробки зображень.
import { useForm } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { VueScrollPicker } from "vue-scroll-picker";

export default {
    components: {
        VueScrollPicker,
        Link,
    },
    // @PropDescription
    // Текст для конвертації, отриманий з зображення.
    props: {
        // @PropDescription
        // Текст в файлі документу.
        text: {
            type: String,
            default: "",
        }
        // @PropDescription
        // Категорія документа.
        category: {
            type: String,
            default: "",
        },
        // @PropDescription
        // Шлях до оригінального зображення.
        originalImage: {
            type: String,
            default: "",

        },
        // @PropDescription
        // Шлях до обробленого зображення.
        processedImage: {
            type: String,
            default: "",

        },
    },
    data() {
        return {
            // @DataDescription
            // Помилки, які можуть виникнути під час обробки файлів.
            error: null,
        };
    },
    methods: {
        // @MethodDescription
        // Обробка зміни файлу та відправка на сервер для конвертації.
        // @param event - подія вибору файлу
        async handleFileChange(event) {
            try {
                console.log("handleFileChange");
                const file = event.target.files[0];
                if (file) {
                    const form = useForm({
                        file: file,
                    });
                    try {
                        await form.post("convert-file-to-text");
                    } catch (error) {
                        this.error = "Помилка під час конвертації файлу";
                        console.error("Error:", error);
                    }
                } else {
                    this.error = "Файл не був обраний";
                    this.convertedText = null;
                }
            } catch (error) {
                console.log(error);
            }
        },
    },
};
</script>

<script setup>
import { onMounted, ref, watch, defineProps } from "vue";
const props = defineProps([
    "text",
    "category",
    "originalImage",
    "processedImage",
]);

const foundSum = ref("0");
const sumFound = ref(false);
const scrollPickerOptions = ref([]);

const scrollPickerValues = ref(foundSum.value.split("").map(Number));

const updatedSum = ref(foundSum.value);

const updateSum = function (index, newValue) {
    let sumArray = updatedSum.value.split("");
    sumArray[index] = newValue;
    updatedSum.value = sumArray.join("");
};

const generateOptions = (index) => {
    try {
        const options = ref([]);

        for (let i = 0; i < index; i++) {
            options.value.push({ value: i, name: i });
        }
        for (let i = index; i < 10; i++) {
            options.value.push({ value: i, name: i });
        }

        return options.value;
    } catch (e) {
        console.log(e);
    }
};

const findSum = async () => {
    console.log("foundSum");
    const pattern = /([CcСс][UuYyУу][MmМмНнHh][AaАа])([^0-9]+)(\d+[\.,]\d+)/;
    if (pattern.test(props.text)) {
        const matches = props.text.match(pattern);
        foundSum.value = matches[3];
        updatedSum.value = foundSum.value;
        sumFound.value = true;

        try {
            console.log("split value to array");
            scrollPickerValues.value = foundSum.value.split("").map(Number);
        } catch (e) {
            console.log(e);
        }
    } else {
        sumFound.value = false;
        foundSum.value = "0";
        scrollPickerOptions.value = [];
    }
};

onMounted(() => {
    findSum();
});

watch(
    () => props.text,
    () => {
        findSum();
    }
);
</script>

<style>
@import "@/css/vue-scroll-picker.css";

.images-container {
    display: flex;
    justify-content: space-between;
}

.image {
    text-align: center;
    flex: 1;
    margin: 0 10px;
}
</style>
