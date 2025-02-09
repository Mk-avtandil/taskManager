<script setup>
import axios from "axios";
import {ref} from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

const showPassword = ref(false);
const showPassword2 = ref(false);
const router = useRouter();
const store = useStore();
const registerData = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const errors = ref({});

const register = async () => {
    try {
        const response = await axios.post('/api/register', {
            name: registerData.value.name,
            email: registerData.value.email,
            password: registerData.value.password,
            password_confirmation: registerData.value.password_confirmation,
        });

        store.dispatch('setToken', response.data.access_token);
        store.dispatch('fetchUser');
        router.push({name: 'home_url'});
    } catch(error) {
        if (error.response && error.response.data && error.response.data.errors) {
            errors.value = error.response.data.errors;
        } else {
            console.error("Something went wrong", error);
        }
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
                    <router-link :to="{name: 'home_url'}" class="nav-link px-2">Home</router-link>
                </li>
            </ul>

            <div class="col-md-3 text-end" v-if="!isAuthenticated">
                <router-link :to="{name: 'login_page_url'}" class="btn btn-outline-primary me-2">Login</router-link>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="login_page w-50 my-5 mx-auto">
            <h2 class=" text-center">Register</h2>
            <form @submit.prevent="register">
                <div class="mb-2">
                    <label for="name" class="form-label">Name:</label>
                    <input class="form-control" type="text" v-model="registerData.name" id="name" required/>
                </div>
                <div v-if="errors.name" class="alert alert-danger my-1">
                    {{ errors.name[0] }}
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label">Email:</label>
                    <input class="form-control" type="email" v-model="registerData.email" id="email" required/>
                </div>
                <div v-if="errors.email" class="alert alert-danger my-1">
                    {{ errors.email[0] }}
                </div>

                <label for="password" class="form-label">Password:</label>
                <div class="input-group mb-2">
                    <input v-model="registerData.password" :type="showPassword ? 'text' : 'password'" class="form-control" type="password" id="password" required/>
                    <button class="input-group-text" id="basic-addon2" @click="showPassword = !showPassword">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                        </svg>
                    </button>
                </div>
                <div v-if="errors.password" class="alert alert-danger my-1">
                    {{ errors.password[0] }}
                </div>

                <label for="passwordConfirm" class="form-label">Confirm password:</label>
                <div class="input-group mb-3">
                    <input :type="showPassword2 ? 'text' : 'password'" class="form-control" type="password" v-model="registerData.password_confirmation" id="passwordConfirm" required/>
                    <button class="input-group-text" id="basic-addon2" @click="showPassword2 = !showPassword2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                        </svg>
                    </button>
                </div>
                <div v-if="errors.password_confirmation" class="alert alert-danger my-1">
                    {{ errors.password_confirmation[0] }}
                </div>

                <button class="btn btn-primary" type="submit">Register</button>
            </form>
        </div>
    </div>
</template>

<style scoped>

</style>
