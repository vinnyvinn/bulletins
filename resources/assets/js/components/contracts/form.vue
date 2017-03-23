<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Contract Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

                            <div class="form-group">
                                <label for="name">Contract Name</label>
                                <input v-model="contract.name" type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select v-model="contract.client_id" name="client_id" id="client_id" class="form-control" required>
                                    <option v-for="client in this.sharedState.state.clients" :value="client.id">{{ client.name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="payment_terms">Payment Terms</label>
                                <select v-model="contract.payment_terms" name="payment_terms" id="payment_terms" class="form-control" required>
                                    <option value="Monthly">Monthly</option>
                                    <option value="Weekly">Weekly</option>
                                    <option value="Daily">Daily</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="route_id">Route</label>
                                <select v-model="contract.route_id" name="route_id" id="route_id" class="form-control" required>
                                    <option v-for="route in sharedState.state.routes" :value="route.id">{{ route.source }} - {{ route.destination }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="start_date">Contract Start</label>
                                <input v-model="contract.start_date" type="text" class="datepicker form-control" id="start_date" name="start_date" required>
                            </div>

                            <div class="form-group">
                                <label for="end_date">Contract End</label>
                                <input v-model="contract.end_date" type="text" class="datepicker form-control" id="end_date" name="end_date" required>
                            </div>

                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <div class="input-group">
                                    <input v-model="contract.quantity" min="0" type="number" class="form-control" id="quantity" name="quantity" describedby="quantity-addon" required>
                                    <span class="input-group-addon" id="quantity-addon">Tonnes</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/contracts" class="btn btn-danger">Back</router-link>
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
            this.setupUI();
        },

        data() {
            return {
                sharedState: window._mainState,
                contract: {
                    id: null,
                    name: null,
                    client_id: null,
                    route_id: null,
                    start_date: null,
                    end_date: null,
                    quantity: null,
                    payment_terms: 'Monthly',
                    client: null,
                    route: null
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.contract = this.sharedState.state.contracts[this.$route.params.id];
                    return;
                }

                this.contract.id = this.sharedState.state.contracts.length;
            },

            setupUI() {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy'
                });

                $('#start_date').datepicker().on('changeDate', (e) => {
                    this.contract.start_date = e.date.toLocaleDateString('en-GB');
                    $('#end_date').datepicker('setStartDate', e.date);
                });

                $('#end_date').datepicker().on('changeDate', (e) => {
                    this.contract.end_date = e.date.toLocaleDateString('en-GB');
                });
            },

            store() {
                if (this.$route.params.id) {
                    this.sharedState.state.contracts[this.$route.params.id] = this.contract;
                    window._router.push({ path: '/contracts' });
                    return;
                }

                this.contract.client = this.sharedState.state.clients.filter(rec => {
                    return rec.id == this.contract.client_id;
                })[0];

                this.contract.route = this.sharedState.state.routes.filter(rec => {
                    return rec.id == this.contract.route_id;
                })[0];

                this.sharedState.state.contracts.push(this.contract);

                window._router.push({ path: '/contracts' })
            }
        }
    }
</script>
