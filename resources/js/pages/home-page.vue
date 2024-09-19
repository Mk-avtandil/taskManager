<script setup>
import axios from "axios";
import { useStore } from "vuex";
import {onMounted, watch, computed, ref} from "vue";
import {useRouter} from "vue-router";

const store = useStore();
const router = useRouter();
const token = computed(() => store.getters.token);
const user = computed(() => store.getters.user);
const isAuthenticated = computed(() => store.getters.isAuthenticated);

const tasks = ref();
const searchQuery = ref('');
const filters = ref({
   status: '',
   priority: ''
});


watch([filters, searchQuery], () => {
    getTasks();
}, { deep: true });


onMounted(async () => {
    await getTasks();
});


const getTasks = async () => {
    try {
        tasks.value = [];
        tasks.value = (await axios.get('/api/tasks', {
            headers: {
                Authorization: `Bearer ${token.value}`
            },
            params: {
                ...filters.value,
                search: searchQuery.value
            }
        })).data;
    } catch (error) {
        console.error("Error fetching tasks: ", error)
    }
}


const deleteTask = async (id) => {
    await axios.delete(`/api/tasks/delete/${id}`, {
        headers: {
            Authorization: `Bearer ${token.value}`
        },
    })
    await getTasks();
}

const truncateText = (text, length) => {
    try {
        if (text.length > length) {
            return text.substring(0, length) + ' ...';
        }
        return text;
    } catch (error) {
        console.error("truncate failed: ", error);
    }
}

const logout = async () => {
    try {
        await store.dispatch('logout');
        getTasks();
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
                <router-link :to="{name: 'home_url'}" class="nav-link px-2 link-secondary">HOME</router-link>
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
        <div class="row">
            <div class="col-3">
                <h1 class="text-success my-3">MY TASKS</h1>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label>Status</label>
                    <select v-model="filters.status" class="form-select">
                        <option value=''>All</option>
                        <option value="new">New</option>
                        <option value="in_progress">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label>Priority</label>
                    <select v-model="filters.priority" class="form-select">
                        <option value=''>All</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label>Search</label>
                    <input v-model="searchQuery" type="text" class="form-control" placeholder="Search by title">
                </div>
            </div>
        </div>
        <div v-for="task in tasks?.data" :key="task.id" class="bg-dark-subtle rounded-2 p-3 mb-2">
            <h2>{{task.title}}</h2>
            <p class="lead">{{truncateText(task.description, 200)}}</p>

            <div class="d-flex justify-content-between">
                <p class="bg-body-tertiary w-25 p-1 rounded ml-1"><span class="text-warning">PRIORITY: </span>{{task.priority}}</p>
                <p class="bg-body-tertiary w-25 p-1 rounded mx-1"><span class="text-warning">STATUS: </span> {{task.status}}</p>
                <p class="bg-body-tertiary w-25 p-1 rounded mx-1"><span class="text-warning">START DATE: </span>{{task.start_date}}</p>
                <p class="bg-body-tertiary w-25 p-1 rounded ml-1"><span class="text-warning">END DATE: </span>{{task.end_date}}</p>
            </div>

            <div class="row">
                <div class="col-6">
                    <router-link :to="{name: 'detail_task_url', params: {id: task.id}}" class="btn btn-primary my-1">DETAIL</router-link>
                    <router-link :to="{name: 'edit_task_url', params: {id: task.id}}" class="btn btn-primary my-1 mx-1">EDIT</router-link>
                    <button class="btn btn-primary my-1" @click.prevent="deleteTask(task.id)">DELETE</button>
                </div>
                <div class="col-6 text-end">
                    <div class="my-3 text-muted">User: {{task.user}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
