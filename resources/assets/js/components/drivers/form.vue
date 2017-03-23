<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Driver Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

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
                                <input v-model="driver.dl_number" type="text" class="form-control text-uppercase" id="dl_number" name="dl_number" required>
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile Number</label>
                                <input v-model="driver.mobile" type="text" class="form-control" id="mobile" name="mobile" required>
                            </div>


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
                    id: null,
                    truck_id: null,
                    name: '',
                    national_id: '',
                    dl_number: '',
                    mobile: ''
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.driver = this.sharedState.state.drivers[this.$route.params.id];
                    return;
                }
                this.driver.id = this.sharedState.state.drivers.length;
            },

            store(route) {
                if (this.$route.params.id) {
                    this.sharedState.state.drivers[this.$route.params.id] = this.driver;
                    window._router.push({ path: '/drivers' });
                    return;
                }
                this.sharedState.state.drivers.push(this.driver);
                window._router.push({ path: '/drivers' })
            }
        }
    }
</script>
