<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Route Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="source">From</label>
                                        <input v-model="route.source" type="text" class="form-control" id="source" name="source" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="destination">To</label>
                                        <input v-model="route.destination" type="text" class="form-control" id="destination" name="destination" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="distance">Distance</label>
                                        <div class="input-group">
                                            <input v-model="route.distance" min="0" type="number" class="form-control" id="distance" name="distance" describedby="distance-addon" required>
                                            <span class="input-group-addon" id="distance-addon">KM</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="amount">Price</label>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="amount-addon">KES</span>
                                            <input v-model="route.amount" min="0" type="number" class="form-control" id="amount" name="amount" describedby="amount-addon" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label for="fuel_required">Is Fuel Required</label>
                                        <div class="input-group">
                                            <input v-model="route.fuel_required" min="1" type="number" class="form-control" id="fuel_required" name="fuel_required" describedby="fuel-addon" required>
                                            <span class="input-group-addon" id="fuel-addon">Litres</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="payment_type">Billing Type</label>
                                        <select v-model="route.payment_type" name="payment_type" id="payment_type" class="form-control" required>
                                            <option value="Per KM">Per KM</option>
                                            <option value="Flat Rate">Flat Rate</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="allowance_amount">Allowance Amount</label>
                                        <input v-model="route.allowance_amount" min="0" type="number" class="form-control" id="allowance_amount" name="allowance_amount" required>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-success">Save</button>
                                        <router-link to="/routes" class="btn btn-danger">Back</router-link>
                                    </div>
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
        mounted() {
            this.checkState();
        },
        data() {
            return {
                sharedState: window._mainState,
                route: {
                    id: null,
                    source: '',
                    destination: '',
                    distance: '',
                    amount: '',
                    fuel_required: 1,
                    payment_type: 'Flat Rate',
                    allowance_amount: 0
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.route = this.sharedState.state.routes[this.$route.params.id];
                    return;
                }

                this.route.id = this.sharedState.state.routes.length;
            },

            store(route) {
                if (this.$route.params.id) {
                    this.sharedState.state.routes[this.$route.params.id] = this.route;
                    window._router.push({ path: '/routes' });
                    return;
                }
                this.sharedState.state.routes.push(this.route);
                window._router.push({ path: '/routes' })
            }
        }
    }
</script>
