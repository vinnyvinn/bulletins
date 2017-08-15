<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Delivery Note</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Vehicle Number</label>
                            <h5>{{ vehicle.plate_number }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trailer Type</label>
                            <h5 v-if="vehicle.trailer">{{ vehicle.trailer.type }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Trailer Attached</label>
                            <h5 v-if="vehicle.trailer">{{ vehicle.trailer.trailer_number }}</h5>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Driver Name</label>
                            <h5 v-if="vehicle.driver">{{ vehicle.driver.first_name }} {{ vehicle.driver.last_name }}</h5>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-sm-5">
                        <h4><strong>Loading Details</strong></h4>
                        <hr>
                        <div class="form-group">
                            <label for="bags_loaded">Bags Loaded</label>
                            <input :disabled="typeof $route.params.unload === 'string'" id="bags_loaded" min="0" v-model="deliveryNote.bags_loaded" type="number" class="form-control input-sm">
                        </div>

                        <div class="form-group">
                            <label for="loading_gross_weight">Gross Weight (Kgs)</label>
                            <input :disabled="typeof $route.params.unload === 'string'" @keyup="updateNote" id="loading_gross_weight" min="0" v-model="deliveryNote.loading_gross_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_tare_weight">Tare Weight (Kgs)</label>
                            <input :disabled="typeof $route.params.unload === 'string'" @keyup="updateNote" id="loading_tare_weight" min="0" v-model="deliveryNote.loading_tare_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_net_weight">Net Weight (Kgs)</label>
                            <input disabled id="loading_net_weight" min="0" v-model="deliveryNote.loading_net_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="loading_weighbridge_number">Delivery Number</label>
                            <input :disabled="typeof $route.params.unload === 'string'" id="loading_weighbridge_number" v-model="deliveryNote.loading_weighbridge_number" type="text" class="form-control input-sm" required>
                        </div>
                    </div>

                    <div class="col-sm-5">
                        <h4><strong>Offloading Details</strong></h4>
                        <hr>

                        <div class="form-group">
                            <label for="offloading_gross_weight">Gross Weight</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" @keyup="updateNote" id="offloading_gross_weight" min="0" v-model="deliveryNote.offloading_gross_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_tare_weight">Tare Weight</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" @keyup="updateNote" id="offloading_tare_weight" min="0" v-model="deliveryNote.offloading_tare_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_net_weight">Net Weight</label>
                            <input disabled id="offloading_net_weight" min="0" v-model="deliveryNote.offloading_net_weight" type="number" class="form-control input-sm">
                        </div>
                        <div class="form-group">
                            <label for="offloading_weighbridge_number">Weighbridge Ticket Number</label>
                            <input :disabled="(typeof $route.params.unload !== 'string') && (typeof $route.params.id !== 'string')" id="offloading_weighbridge_number" v-model="deliveryNote.offloading_weighbridge_number" type="text" class="form-control input-sm" required>
                        </div>
                        <div class="form-group">
                          <label for="temporary_driver">Temporary Driver</label>
                          <select class="form-control input-sm" v-model="deliveryNote.temporary_driver">
                            <option value="" disabled> Select A driver</option>
                            <option v-for="driver in drivers":value="driver.id">{{ driver.first_name }} {{ driver.last_name }}</option>
                          </select>
                         </div>
                    </div>

                    <div class="col-sm-2">
                        <h4><strong>Narration</strong></h4>
                        <hr>
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea v-model="deliveryNote.narration" name="narration" id="narration" rows="15" class="form-control input-sm"></textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success">Process</button>
                    <router-link to="/ls/offloading" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                unloading: false,
                vehicle: {
                  trailer: {},
                  driver: {},
                  contract: {}
                },
                deliveryNote: {
                    station_id: window.Laravel.station_id,
                    vehicle_id: '',
                    contract_id: '',
                    narration: '',
                    bags_loaded: 0,
                    loading_gross_weight: 0,
                    loading_tare_weight: 0,
                    loading_net_weight: 0,
                    loading_weighbridge_number: '',
                    offloading_gross_weight: 0,
                    offloading_tare_weight: 0,
                    offloading_net_weight: 0,
                    offloading_weighbridge_number: '',
                    temporary_driver: ''
                },
                drivers:[]
            };
        },

        created() {
            if (! this.$route.params.id && ! this.$route.params.unload &&  ! this.$root.can('create-delivery')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && ! this.$root.can('edit-delivery')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id || this.$route.params.unload) {
                this.checkState();
                return;
            }
            this.$root.isLoading = true;
            http.get('/api/lsdelivery/create/?id=' + this.$route.params.new).then((response) => {
                this.vehicle = response.vehicle;
                this.deliveryNote.vehicle_id = this.vehicle.id;
                this.deliveryNote.contract_id = this.vehicle.contract_id;
                this.drivers = response.drivers;
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        methods: {
            updateNote() {
              if(parseFloat(this.deliveryNote.loading_gross_weight) >= parseFloat(this.deliveryNote.loading_tare_weight)) {
                this.deliveryNote.loading_net_weight = parseFloat(this.deliveryNote.loading_gross_weight) - parseFloat(this.deliveryNote.loading_tare_weight);
                this.deliveryNote.offloading_net_weight = parseFloat(this.deliveryNote.offloading_gross_weight) - parseFloat(this.deliveryNote.offloading_tare_weight);
              } else {
                alert2(this.$root, ['Tare Weight cannot be more than the gross weight'], 'danger');
                this.deliveryNote.loading_tare_weight = 0;
              }

            },

            checkState() {
                this.$root.isLoading = true;
                let id = this.$route.params.unload ? this.$route.params.unload : this.$route.params.id;

                return http.get('/api/lsdelivery/' + id).then((response) => {
                    this.deliveryNote = response.delivery;
                    this.$root.isLoading = false;
                });
            },
            store() {
                this.$root.isLoading = true;
                let request = null;


                if (this.$route.params.id) {
                    request = http.put('/api/lsdelivery/' + this.$route.params.id, this.deliveryNote);
                } else if (this.$route.params.unload) {
                    request = http.put('/api/lsdelivery/' + this.$route.params.unload, this.deliveryNote);
                } else {
                    request = http.post('/api/lsdelivery', this.deliveryNote);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/ls/delivery' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
