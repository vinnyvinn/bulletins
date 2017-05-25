<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trailer Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

                            <div class="form-group">
                                <label for="trailer_number">Trailer Number</label>
                                <input v-model="trailer.trailer_number" type="text" class="form-control text-uppercase" id="trailer_number" name="trailer_number" required>
                            </div>

                            <div class="form-group">
                                <label for="make">Make</label>
                                <input v-model="trailer.make" type="text" class="form-control text-uppercase" id="make" name="make" required>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <input v-model="trailer.type" type="text" class="form-control text-uppercase" id="type" name="type" required>
                            </div>

                            <udf module="Trailers" v-on:udfAdded="addUdfToObject" :state="trailer"></udf>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/trailers" class="btn btn-danger">Back</router-link>
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
                trailer: {
                    trailer_number: '',
                    type: '',
                    make: '',
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/trailer/' + this.$route.params.id).then((response) => {
                        this.trailer = response.trailer;
                    });
                }
            },

            store() {
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/trailer/' + this.$route.params.id, this.trailer);
                } else {
                    request = http.post('/api/trailer', this.trailer);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/trailers' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
            addUdfToObject (slug) {
              Vue.set(this.trailer,slug,'');
            }
        }
    }
</script>
