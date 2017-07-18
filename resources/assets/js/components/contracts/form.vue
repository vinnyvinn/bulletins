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
                                <select v-model="contract.client_id" name="client_id" id="client_id" class="form-control select2" required>
                                    <option v-for="client in clients" :value="client.DCLink">{{ client.Name }} ({{ client.Account }})</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="rate">Rate</label>
                                <select v-model="contract.rate" name="rate" id="rate" class="form-control select2" required>
                                    <option value="Per Hour">Per Hour</option>
                                    <option value="Per KM">Per KM</option>
                                    <option value="Per Tonne">Per Tonne</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="amount">Price {{ contract.rate }}</label>
                                <input v-model="contract.amount" type="number" class="form-control" id="amount" name="amount" required>
                            </div>

                            <div class="form-group">
                                <label for="route_id">Route</label>
                                <select v-model="contract.route_id" name="route_id" id="route_id" class="form-control select2" required>
                                    <option v-for="route in routes" :value="route.id">{{ route.source }} - {{ route.destination }} ({{ route.distance }} KM)</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stock_item_id">Billable Item</label>
                                <select v-model="contract.stock_item_id" name="stock_item_id" id="stock_item_id" class="form-control select2" required>
                                    <option v-for="item in stockItems" :value="item.StockLink">{{ item.Description_1 }}</option>
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

                            <udf module="Contracts" :state="contract" :uploads="uploads"></udf>


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
        created() {
            http.get('/api/contract/create').then((response) => {
                this.clients = response.clients;
                this.stockItems = response.stockItems;
                this.routes = response.routes;
            });
        },

        mounted() {
            this.checkState();
        },

        data() {
            return {
                clients: [],
                routes: [],
                uploads: [],
                stockItems: [],
                contract: {
                    ignore_delivery_note: false,
                    name: null,
                    rate: 'Per Tonne',
                    amount: null,
                    client_id: null,
                    stock_item_id: null,
                    route_id: null,
                    start_date: null,
                    end_date: null,
                    quantity: null,
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/contract/' + this.$route.params.id).then((response) => {
                        this.contract = response.contract;
                        this.contract.ignore_delivery_note = response.contractignore_delivery_note != 0;
                        this.contract.start_date = this.formatDate(this.contract.start_date);
                        this.contract.end_date = this.formatDate(this.contract.end_date);
                        this.setupUI();
                    });

                    return;
                }
                this.setupUI();
            },

            formatDate(date) {
                date = date.split('-');

                return date[2] + '/' + date[1] + '/' + date[0];
            },

            setupUI() {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'dd/mm/yyyy',
                    todayHighlight: true,
                });

                $('#start_date').datepicker().on('changeDate', (e) => {
                    this.contract.start_date = e.date.toLocaleDateString('en-GB');
                    $('#end_date').datepicker('setStartDate', e.date);
                });

                $('#end_date').datepicker().on('changeDate', (e) => {
                    this.contract.end_date = e.date.toLocaleDateString('en-GB');
                });

                setTimeout(() => {
                    $('#client_id').select2().on('change', e => this.contract.route_id = e.target.value);
                    $('#route_id').select2().on('change', e => this.contract.route_id = e.target.value);
                    $('#stock_item_id').select2().on('change', e => this.contract.stock_item_id = e.target.value);
                }, 1000);
            },

            store() {
                let request = null;
                let data = mapToFormData(this.contract, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/contract/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/contract', data, true);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/contracts' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
            addUdfToObject (slug) {
              Vue.set(this.contract,slug,'');
            }
        }
    }
</script>
