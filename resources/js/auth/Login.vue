<template>
    <div class="w-50 m-auto">
        <div class=" card card-body">
            <form>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input class ="form-control" type="text" name="email" placeholder="Enter you Email" v-model="email" 
                    :class="[{'is-invalid': errorFor('email')}]"/>
                    <v-error :errors="errorFor('email')"></v-error>
                </div>
                <div class="form-group">
                    <label for="email">Password</label>
                    <input class ="form-control" type="text" name="password" placeholder="Enter you Password" v-model="password"
                     :class="[{'is-invalid': errorFor('email')}]"/>
                    <v-error :errors="errorFor('password')"></v-error>
                </div>
                <button @click.prevent ="login()" class="btn btn-primary btn-block" type="submit" :disabled="loading">
                    Log-In
                </button>
            </form>
            <hr/>
            <div>
                No Account Yet?
                <router-link :to= "{path: 'home'}">Register</router-link>
            </div>
            <div>
                Forgotten Password?
                <router-link :to= "{path: 'home'}">Reset Password</router-link>
            </div>
        </div>
    </div>
</template>

<script>
import validationErrors from '../shared/mixins/validationErrors';
import { logIn, logOut } from "../shared/utils/auth"

export default {
    mixins:[validationErrors],
    data() {
        return {
            email: null,
            password: null,
            loading:false,
        }
    },
    methods:{
        async login() {
            this.loading = true
            this.errors = null

            try {
                await axios.get('/sanctum/csrf-cookie')

                await axios.post('/login', {
                    email: this.email,
                    password: this.password
                })

                logIn()
                this.$store.dispatch('loadUser')
                this.$router.push({name: 'home'})
            } catch (error) {
                console.error(error);
                this.errors = error.response && error.response.data.errors
            }

            this.loading =false
        }
    }
}
</script>