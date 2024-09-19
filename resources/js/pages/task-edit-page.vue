<script setup>
import axios from "axios";
import {useRoute} from "vue-router";
import {computed, onMounted, ref} from "vue";
import {useStore} from "vuex";

const store = useStore();
const route = useRoute();

const token = computed(() => store.getters.token);
const user = computed(() => store.getters.user);
const isAuthenticated = computed(() => store.getters.isAuthenticated);

const task = ref({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    priority: '',
    status: '',
    user_id: ''
});

onMounted(async () => {
    task.value = (await axios.get(`/api/tasks/edit/${route.params.id}`, {
        headers: {
            Authorization: `Bearer ${token.value}`
        }
    })).data;
});

const updateTask = async () => {
    await axios.put(`/api/tasks/update/${route.params.id}`, {
        title: task.value.title,
        description: task.value.description,
        start_date: task.value.start_date,
        end_date: task.value.end_date,
        priority: task.value.priority,
        status: task.value.status,
        user_id: task.value.user_id
    },
        {
        headers: {
            Authorization: `Bearer ${token.value}`
        },
    })
};

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

    <div class="container my-3">
        <h3 class="mb-3">UPDATE TASK</h3>
        <form action="" method="POST">
            <div class="form-group">
                <label>Title</label>
                <input v-model="task.title" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea cols="20" rows="5" v-model="task.description" type="text" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Start date</label>
                <input v-model="task.start_date" type="date" class="form-control">
            </div>
            <div class="form-group">
                <label>End date</label>
                <input v-model="task.end_date" type="date" class="form-control">
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select v-model="task.priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select v-model="task.status" class="form-select">
                    <option value="new">New</option>
                    <option value="in_progress">In progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="form-group my-3">
                <button @click.prevent="updateTask()" class="btn btn-primary" type="submit">Сохранить</button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
