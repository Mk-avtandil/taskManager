import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/",
        name: "home_url",
        component: () => import("../pages/home-page.vue"),
    },
    {
        path: "/task/detail/:id",
        name: "detail_task_url",
        component: () => import("../pages/task-detail-page.vue"),
    },
    {
        path: "/task/create",
        name: "create_task_url",
        component: () => import("../pages/task-create-page.vue"),
    },
    {
        path: "/task/edit/:id",
        name: "edit_task_url",
        component: () => import("../pages/task-edit-page.vue"),
    },
    {
        path: "/register",
        name: "register_page_url",
        component: () => import("../pages/register-page.vue"),
    },
    {
        path: "/login",
        name: "login_page_url",
        component: () => import("../pages/login-page.vue"),
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
