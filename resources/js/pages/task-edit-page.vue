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

const errors = ref({});
const successMessage = ref('');
const data = ref({
    title: '',
    description: '',
    start_date: '',
    end_date: '',
    priority: '',
    status: '',
    user_id: ''
});


onMounted(async () => {
    try {
        data.value = (await axios.get(`/api/tasks/edit/${route.params.id}`, {
            headers: {
                Authorization: `Bearer ${token.value}`
            }
        })).data;
        successMessage.value = '';
    } catch (error) {
        console.error("Failed to get task: ", error);
    }
});


const updateTask = async () => {
    try {
        errors.value = {};
        successMessage.value = '';

        await axios.put(`/api/tasks/update/${route.params.id}`, {
                title: data.value.title,
                description: data.value.description,
                start_date: data.value.start_date,
                end_date: data.value.end_date,
                priority: data.value.priority,
                status: data.value.status,
                user_id: data.value.user_id
            },
            {
                headers: {
                    Authorization: `Bearer ${token.value}`
                },
            });
        successMessage.value = 'Task edited successfully!';
    } catch (error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Failed to create task');
        }
    }
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
        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>
        <form action="" method="POST">
            <div class="form-group">
                <label>Title</label>
                <input v-model="data.title" type="text" class="form-control">
            </div>
            <div v-if="errors.title" class="alert alert-danger my-1">
                {{ errors.title[0] }}
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea cols="20" rows="5" v-model="data.description" type="text" class="form-control"></textarea>
            </div>
            <div v-if="errors.description" class="alert alert-danger my-1">
                {{ errors.description[0] }}
            </div>

            <div class="form-group">
                <label>Start date</label>
                <input v-model="data.start_date" type="date" class="form-control">
            </div>
            <div v-if="errors.start_date" class="alert alert-danger my-1">
                {{ errors.start_date[0] }}
            </div>

            <div class="form-group">
                <label>End date</label>
                <input v-model="data.end_date" type="date" class="form-control">
            </div>
            <div v-if="errors.end_date" class="alert alert-danger my-1">
                {{ errors.end_date[0] }}
            </div>

            <div class="form-group">
                <label>Priority</label>
                <select v-model="data.priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div v-if="errors.priority" class="alert alert-danger my-1">
                {{ errors.priority[0] }}
            </div>

            <div class="form-group">
                <label>Status</label>
                <select v-model="data.status" class="form-select">
                    <option value="new">New</option>
                    <option value="in_progress">In progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div v-if="errors.status" class="alert alert-danger my-1">
                {{ errors.status[0] }}
            </div>

            <div class="form-group my-3">
                <button @click.prevent="updateTask()" class="btn btn-primary" type="submit">Сохранить</button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
