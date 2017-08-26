<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Delivery Note</strong>
        </div>

        <div class="panel-body">
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
                  <div class="col-sm-6">
                    Deliveries Done
                    <table class="table no-wrap">
                      <caption>Total Deliveries: </caption>
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Delivery #</th>
                          <th>Temporary Driver</th>
                          <th>When</th>
                          <th>Created By</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(delivery, index) in deliveries">
                          <td>{{ index + 1 }}</td>
                          <td>DL - {{ delivery.id}}</td>
                          <td>
                            <span v-if="delivery.temporary_driver">{{ delivery.temporary_driver.first_name }}{{delivery.temporary_driver.last_name}}</span>
                          </td>
                          <td>{{ humanDate(delivery.created_at) }}</td>
                          <td>{{ delivery.user.first_name}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>#</th>
                          <th>Delivery #</th>
                          <th> Temporary Driver </th>
                          <th>When</th>
                          <th>Created By</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>

                  <div class="col-sm-6">
                    Paid out Mileages
                    <table class="table no-wrap">
                      <caption>Total Amount Paid: </caption>
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Delivery #</th>
                          <th>When</th>
                          <th>Advance</th>
                          <th>Amount</th>
                          <th>Paid By</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(mileage, index) in mileages">
                          <td>{{ index + 1 }}</td>
                          <td>ML - {{ mileage.id }}</td>
                          <td>{{ humanDate(mileage.created_at) }}</td>
                          <td v-if="mileage.is_advance == 1"><span class="label label-success">Yes</span></td>
                          <td v-if="mileage.is_advance == 0"></td>
                          <td>{{ mileage.amount }}</td>
                          <td>{{ mileage.user.first_name}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>#</th>
                          <th>Delivery #</th>
                          <th>When</th>
                          <th>Advance</th>
                          <th>Amount</th>
                          <th>Paid By</th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>

                <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                          <label for="amount">Amount</label><br>
                          <input type="number" min=0 id="amount" v-model="mileage.amount"><br>
                          <label for="is_advance"><input type="checkbox" id="is_advance" v-model="mileage.is_advance"> Is Advance?</label>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="narration">Narration</label>
                            <textarea name="narration" id="narration" class="form-control input-sm" v-model="mileage.narration"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="rate_per_trip">Rate per trip (Ksh)</label>
                        <input type="number" id="rate_per_trip" v-model="rate_per_trip" @keyup="calculateMileageBalance()" disabled>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <h5>Mileage: {{ deliveries.length }} trips * {{ rate_per_trip }}: Ksh. {{ mileage_total }}</h5>
                      <h5>Advanced Mileage: {{ advanced_mileage }}</h5>
                      <h5>Mileage Paid: {{ mileage_paid }}</h5>
                      <hr>
                      <h2>Balance: Ksh. <strong>{{ mileage_total - advanced_mileage - mileage_paid }}</strong></h2>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-success">Process</button>
                    <router-link :to="'/ls/mileage/' + mileage.contract_id" class="btn btn-danger">Back</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                rate_per_trip: 0,
                mileage_total: 0,
                advanced_mileage: 0,
                mileage_paid: 0,
                vehicle: {
                  driver: {}
                },
                mileages: [],
                deliveries: [],
                mileage: {
                  contract_id: '',
                  vehicle_id: '',
                  amount: 0,
                  is_advance: false,
                  narration: ''
                },
            };
        },

        created() {
            this.$root.isLoading = true;
            http.get('/api/lsmileage/create/' + this.$route.params.truck + '/' + this.$route.params.contract).then((response) => {
                this.mileage.contract_id = this.$route.params.contract;
                this.mileage.vehicle_id = this.$route.params.truck;
                this.vehicle = response.vehicle;
                this.mileages = response.mileages;
                this.deliveries = response.deliveries;
                this.rate_per_trip = response.drivers_rate.drivers_rate ? response.drivers_rate.drivers_rate : 0;

                this.calculateMileageBalance();
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },


        computed: {

        },

        methods: {
          humanDate(date) {
            return moment(date).format('ll');
          },

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

                return http.get('/api/delivery/' + id).then((response) => {
                    this.deliveryNote = response.delivery;
                    this.journeys = response.journeys;
                    this.$root.isLoading = false;
                });
            },
            store() {
                this.$root.isLoading = true;

                http.post('/api/lsmileage', this.mileage).then((response) => {
                  this.mileages = response.lsmileages;
                  this.calculateMileageBalance();
                  this.$root.isLoading = false;
                  alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });

            },

            calculateMileageBalance () {
              this.advanced_mileage = 0;
              this.mileage_paid = 0;
              this.mileage_total = this.rate_per_trip * this.deliveries.length;
              for(let i = 0; i<this.mileages.length; i++) {
                if(this.mileages[i].is_advance == 1) {
                  this.advanced_mileage = parseInt(this.advanced_mileage) + parseInt(this.mileages[i].amount);
                }
              }

              for(let i = 0; i<this.mileages.length; i++) {
                if(this.mileages[i].is_advance == 0) {
                  this.mileage_paid = parseInt(this.mileage_paid) + parseInt(this.mileages[i].amount);
                }
              }
            }
        }
    }
</script>
