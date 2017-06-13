<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label for="date">Date</label>
                      <input type="text" v-model="fuel.date" class="form-control input-sm datepicker" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                      <label for="journey_id">Journey</label>
                      <select class="" name="journey_id" v-model="fuel.journey_id" class="form-control input-sm select2" required @change="selectJourney">
                        <option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>
                      </select>
                    </div>

                  </div>

                    <div class="col-sm-3">
                      <strong>Route: RT-{{ current_route.id}}</strong><br>
                      From: {{ current_route.source }}<br>
                      To: {{ current_route.destination }}<br>
                      Deliveries:<br>
                      <hr>
                    </div>

                    <div class="col-sm-3">
                      <strong>Vehicle</strong><br>
                      Reg.No: {{ current_vehicle.plate_number }}<br>
                      Model: {{ current_vehicle.model}}<br>
                      Trailer Attached: <input type="checkbox" name="trailer_attached" :checked="current_vehicle.trailer"><br>
                      <div class="" v-if="current_trailer">
                        Trailer: {{ current_trailer.trailer_number }}<br>
                        Trailer Category: {{ current_trailer.type }} <br>
                      </div>
                      <hr>
                    </div>

                    <div class="col-sm-3">
                      <strong>Driver</strong><br>
                      Passport: <img src="" alt=""> <br>
                      Name: {{ current_driver.first_name  }}<br>
                      <hr>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="current_fuel">Current Fuel (Litres)</label>
                        <input type="number" name="current_fuel" class="form-control input-sm" v-model="fuel.current_fuel" @keyup="calculateTotal">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label>Standard Quantity for this Route (Litres)</label><br>
                        <h4>{{ current_route.fuel_required }}</h4>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="fuel_issued">Requested Quantity (Litres)</label>
                        <input type="text" name="fuel_requested" class="form-control input-sm" v-model="fuel.fuel_requested">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="fuel_issued">Fuel Issued (Litres)</label>
                        <input type="number" name="fuel_issued" class="form-control input-sm" v-model="fuel.fuel_issued" @keyup="calculateTotal">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="fuel_issued">Total Fuel in Tank(Litres)</label>
                        <input type="text" name="fuel_total" class="form-control input-sm" v-model="fuel.fuel_total">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="narration">Description</label>
                        <textarea name="narration" class="form-control input-sm" v-model="fuel.narration"></textarea>
                      </div>
                    </div>
                </div>

                <div class="form-group pull-right">
                    <button class="btn btn-success">Save</button>
                    <router-link to="/fuel" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        created() {
            http.get('/api/fuel/create').then((response) => {
                this.journeys = response.journeys;
            });
        },

        mounted() {
            this.checkState();
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        data() {
            return {
                journeys: [],
                current_journey: {},
                current_driver: {},
                current_vehicle: {},
                current_trailer: {},
                current_route: {},
                fuel: {
                    journey_id: '',
                    date: '',
                    current_fuel: 0,
                    fuel_requested: 0,
                    fuel_issued: 0,
                    fuel_total: 0,
                    narration: '',
                    status: 'Pending Approval'
                }
            };
        },
        computed: {
          selectJourney() {
            let journey = this.journeys.filter(e => e.id == this.fuel.journey_id);
            if (journey.length) {
              this.current_driver = JSON.parse(JSON.stringify(journey[0].driver));
              this.current_vehicle = JSON.parse(JSON.stringify(journey[0].truck));
              this.current_trailer = JSON.parse(JSON.stringify(journey[0].truck.trailer));
              this.current_route = JSON.parse(JSON.stringify(journey[0].route));

                return this.current_journey = JSON.parse(JSON.stringify(journey[0]));
            }
          }
        },

        methods: {
          setupUI() {
              $('.datepicker').datepicker({
                  autoclose: true,
                  format: 'dd/mm/yyyy',
                  todayHighlight: true,
              });

              $('#date').datepicker().on('changeDate', (e) => {
                  this.fuel.date = e.date.toLocaleDateString('en-GB');
              });
          },

          checkState() {
              if (this.$route.params.id) {
                      http.get('/api/fuel/' + this.$route.params.id).then((response) => {
                          this.fuel = response.fuel;
                          this.setupUI();
                      });
              }
              this.setupUI();
          },
          calculateTotal() {
            return this.fuel.fuel_total = parseInt(this.fuel.fuel_issued) + parseInt(this.fuel.current_fuel);
          },

            store() {
                this.$root.isLoading = true;
                let request = null;

                let body = Object.assign({}, this.fuel)

                if(this.$route.params.id) {
                  request = axios.put('/api/fuel/'+ this.$route.params.id, body)
                } else {
                  request = axios.post('/api/fuel', body)
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/fuel' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
