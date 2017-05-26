<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Driver Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="name">Name</label>
                                <input v-model="driver.name" type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="name">National ID</label>
                                <input v-model="driver.national_id" type="number" class="form-control" id="national_id" name="national_id" required>
                            </div>

                            <div class="form-group">
                                <label for="dl_number">Drivers License Number</label>
                                <input v-model="driver.dl_number" type="text" class="form-control text-uppercase" id="dl_number" name="dl_number">
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile Number</label>
                                <input v-model="driver.mobile" type="text" class="form-control" id="mobile" name="mobile">
                            </div>

                            <udf module="Drivers" v-on:udfAdded="addUdfToObject" :state="driver"></udf>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/drivers" class="btn btn-danger">Back</router-link>
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
        mounted() {
            this.checkState();
        },
        data() {
            return {
                sharedState: window._mainState,
                driver: {
                    _token: window.Laravel.csrfToken,
                    _method: 'POST',
                    name: '',
                    national_id: '',
                    dl_number: '',
                    mobile: ''
                },
                errors: [],
                level: 'danger',
                showError: false
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.driver._method = 'PUT';
                    http.get('/api/driver/' + this.$route.params.id).then((response) => {
                        this.driver = response.driver;
                    });
                }
            },

            store() {
                this.$root.isLoading = true;
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/driver/' + this.$route.params.id, this.driver);
                } else {
                    request = http.post('/api/driver', this.driver);
                    request = http.uploadFile('.image', '/api/driver');
                }
                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/drivers' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
            addUdfToObject (slug) {
              Vue.set(this.driver,slug,'');
            }
        }
    }
</script>
