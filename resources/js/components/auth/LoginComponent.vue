<template>
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login Aurablog</div>

                    <div class="card-body">
                        <div class="form">

                            <div v-show="error" class="alert alert-danger" role="alert">
                                {{ message_error }}
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input v-model="email" id="email" type="email" class="form-control " name="email"
                                           required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input v-model="password" id="password" type="password" class="form-control"
                                           name="password" required autocomplete="current-password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input v-model="remember_me" class="form-check-input" type="checkbox"
                                               name="remember" id="remember">

                                        <label class="form-check-label" for="remember">
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" @click="login" class="btn btn-primary">
                                        Login
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "LoginComponent",
        data() {
            return {
                email: null,
                password: null,
                remember_me: false,
                message_error: "",
                error: false
            }
        },
        methods: {
            login() {

                let body = {
                    email: this.email,
                    password: this.password,
                    remember_me: this.remember_me
                };
                this.error = false;

                this.$store
                    .dispatch("retrieveToken", body)
                    .then(response => {
                        this.$router.push({ name: "Dashboard" });
                    })
                    .catch(error => {
                        this.error = error.response.data;
                    });



            }
        },
        created(){
            if (this.$store.getters.loggedIn){
                this.$router.push({ name: "Dashboard" });
            }
        }
    }
</script>

<style scoped>

</style>
