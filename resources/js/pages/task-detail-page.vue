<script setup>
import axios from "axios";
import { useStore } from "vuex";
import {useRoute} from "vue-router";
import {computed, onMounted, ref} from "vue";

const route = useRoute();
const store = useStore();

const token = computed(() => store.getters.token);
const user = computed(() => store.getters.user);
const isAuthenticated = computed(() => store.getters.isAuthenticated);

const task = ref({});

onMounted(async () => {
    getTask();
});

const getTask = async () => {
    try {
        task.value = (await axios.get(`/api/tasks/detail/${route.params.id}`, {
            headers: {
                Authorization: `Bearer ${token.value}`
            }
        })).data
    } catch (error) {
        console.error('Failed to get task: ', error);
    }
}

const logout = async () => {
    try {
        await store.dispatch('logout');
    } catch (error) {
        console.error("Logout failed: ", error);
    }
}
</script>

<template>
    <header class="bg-body-tertiary">
        <div class="container d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <h5 class="text-body-emphasis">TASK MANAGER</h5>
                </a>
            </div>

            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
                <li>
                    <router-link :to="{name: 'home_url'}" class="nav-link px-2">HOME</router-link>
                </li>
                <li>
                    <router-link :to="{name: 'create_task_url'}"  class="nav-link px-2">NEW TASK</router-link>
                </li>
            </ul>

            <div class="col-md-3 text-end" v-if="!isAuthenticated">
                <router-link :to="{name: 'login_page_url'}" class="btn btn-outline-primary me-2">Login</router-link>
                <router-link :to="{name: 'register_page_url'}" class="btn btn-primary">Sign-up</router-link>
            </div>
            <div class="col-md-3 text-end" v-if="isAuthenticated">
                <button type="button" @click="logout" class="btn btn-primary">Logout</button>
            </div>
        </div>
    </header>

    <div class="container">
        <h1 class="text-success my-4">{{task.title}}</h1>
        <div class="jumbotron">
            <p class="lead">{{task.description}}</p>
            <hr class="my-4">

            <table class="table w-25">
                <tr>
                    <th>PRIORITY: <span class="text-warning">{{task.priority}}</span></th>
                </tr>
                <tr>
                    <th>STATUS: <span class="text-warning">{{task.status}}</span></th>
                </tr>
                <tr>
                    <th>START DATE: <span class="text-warning">{{task.start_date}}</span></th>
                </tr>
                <tr>
                    <th>END DATE: <span class="text-warning">{{task.end_date}}</span></th>
                </tr>
                <tr>
                    <th>USER NAME: <span class="text-info">{{task.user?.name}}</span></th>
                </tr>
                <tr>
                    <th>USER EMAIL: <span class="text-info">{{task.user?.email}}</span></th>
                </tr>
            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
