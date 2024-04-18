import { createRouter, createWebHashHistory } from "vue-router";
import Dashboard from "./Pages/Dashboard.vue";
import FileUpload from "./Pages/FileUpload.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/file-upload",
            component: FileUpload,
            name: "FuleUpload",
        },
        {
            path: "/dashboard",
            component: Dashboard,
            name: "Dashboard",
        },
    ],
});
