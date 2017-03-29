<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Allocation Details</strong>
                    </div>

                    <div class="panel-body">
                        <form action="#" role="form" @submit.prevent="store">

                            <div class="form-group">
                                <label for="contract_id">Contract</label>
                                <select v-model="allocation.contract_id" name="contract_id" id="contract_id" class="form-control" required>
                                    <option v-for="contract in contracts" :value="contract.id">{{ contract.name }}</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="! editing">
                                <label for="truck_id">Available Trucks</label>
                                <select v-model="allocation.truck_id" name="truck_id" id="truck_id" class="form-control" required>
                                    <option v-for="truck in trucks" :value="truck.id">{{ truck.plate_number }} ({{ truck.max_load }} Tonnes)</option>
                                </select>
                            </div>

                            <div class="form-group" v-if="editing">
                                <label for="truck_id">Truck</label>
                                <h5>{{ allocation.plate_number }} ({{ allocation.max_load }} Tonnes)</h5>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/allocation" class="btn btn-danger">Back</router-link>
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
            this.checkState();
        },

        data() {
            return {
                editing: false,
                trucks: [],
                contracts: [],
                allocation: {
                    contract_id: null,
                    truck_id: null,
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.editing = true;
                    http.get('/api/allocation/' + this.$route.params.id + '/edit').then((response) => {
                        this.allocation = response.allocation;
                        this.contracts = response.contracts;
                    });
                    return;
                }

                http.get('/api/allocation/create').then((response) => {
                    this.trucks = response.trucks;
                    this.contracts = response.contracts;
                });
            },

            store() {
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/allocation/' + this.$route.params.id, this.allocation);
                } else {
                    request = http.post('/api/allocation', this.allocation);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/allocation' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
