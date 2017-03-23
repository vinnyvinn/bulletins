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
                                    <option v-for="contract in this.sharedState.state.contracts" :value="contract.id">{{ contract.name }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="truck_id">Available Trucks</label>
                                <select v-model="allocation.truck_id" name="truck_id" id="truck_id" class="form-control" required>
                                    <option v-for="truck in this.sharedState.state.trucks" :value="truck.id">{{ truck.plate_number }}</option>
                                </select>
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
        mounted() {
            this.checkState();
        },

        data() {
            return {
                sharedState: window._mainState,
                allocation: {
                    id: null,
                    contract_id: null,
                    truck_id: null,
                    contract: null,
                    truck: null
                }
            };
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.allocation = this.sharedState.state.allocations[this.$route.params.id];
                    return;
                }

                this.allocation.id = this.sharedState.state.allocations.length;
            },

            store() {
                if (this.$route.params.id) {
                    this.sharedState.state.allocations[this.$route.params.id] = this.allocation;
                    window._router.push({ path: '/contracts' });
                    return;
                }

                this.allocation.contract = this.sharedState.state.contracts.filter((rec) => {
                    return rec.id == this.allocation.contract_id;
                })[0];

                let truck = this.sharedState.state.trucks.filter((rec) => {
                    return rec.id == this.allocation.truck_id;
                })[0];

                truck.currentState = 'Pre-Loading';
                this.sharedState.state.trucks[truck.id] = truck;

                this.allocation.truck = truck;

                this.sharedState.state.allocations.push(this.allocation);

                window._router.push({ path: '/allocation' })
            }
        }
    }
</script>
