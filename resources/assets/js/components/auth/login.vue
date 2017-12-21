<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>
                    <div class="panel-body">
                        <form class="form-horizontal" action="/login" @submit.prevent="login" role="form" method="POST">
                            <input type="hidden" name="_token" :value="$root.csrf">
                            <div class="form-group">
                                <label for="username" class="col-md-4 control-label">Username</label>

                                <div class="col-md-6">
                                    <input id="username" type="text" class="form-control" name="username" v-model="user.username" required autofocus>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" v-model="user.password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <!--<a class="btn btn-link" href="/forgot">-->
                                        <!--Forgot Your Password?-->
                                    <!--</a>-->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                user: {
                    username: '',
                    password: '',
                    grant_type: 'password',
                    client_id: 2,
                    client_secret: 'twuq6czARQXNX2gYosgMpOQlBpumydKGqcjdq4xx',
                    scope: ''
                },
            };
        },

        methods: {
            login() {
                this.$root.isLoading = true;
                http.post('/login', this.user).then(response => {
                    this.$root.isLoading = false;

                    alert2(this.$root, ['Successfully signed in.'], 'success');
                    localStorage.setItem('foeiwafwfuwe', response.access_token);
                    localStorage.setItem('fewuia32rfwe', JSON.stringify(response.user));

                   setTimeout(() => window.location = '/', 1000);
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [Object.values(JSON.parse(error.message))[1]], 'danger');

                });


            }
        }
    }
</script>
