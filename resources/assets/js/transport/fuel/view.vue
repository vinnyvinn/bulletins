<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                  <div class="col-xs-3">
                      Date: {{ fuel.date }}<br>
                      Journey: JRNY-{{ fuel.journey_id }}<br>
                      Status: {{ fuel.status }}
                      <hr>
                      <strong>Route</strong>
                      Route: Route: RT-{{ current_route.id}}<br>
                      From: {{ current_route.source }}<br>
                      To: {{ current_route.destination }}<br>
                      <hr>
                      <strong>Vehicle</strong>
                      Reg.No: {{ current_vehicle.plate_number }}<br>
                      Model: {{ current_vehicle.model}}<br>
                      Trailer Attached: <input type="checkbox" name="trailer_attached" :checked="current_vehicle.trailer"><br>
                      <div class="" v-if="current_trailer">
                        Trailer: {{ current_trailer.trailer_number }}<br>
                        Trailer Category: {{ current_trailer.type }} <br>
                      </div>
                  </div>

                    <div class="col-xs-3">
                      <strong>Driver</strong><br>
                      <img :src="getSource()" alt="" width="100" height="100"> <br>
                      Passport: <br>
                      Name: {{ current_driver.first_name  }}<br>
                      Id No: {{ current_driver.identification_number }}<br>
                      Mobile No: {{ current_driver.mobile_phone                                                                                                                                                                                                                                                                                                                                                                                                        }}<br>
                      DL number: {{ current_driver.dl_number }}<br>
                    </div>

                    <div class="col-xs-3">
                      <strong>Fuel</strong><br>
                      Standard Quantity for this route (Ltrs): {{ current_route.fuel_required }}<br>
                      Current Fuel (Litres): {{ fuel.current_fuel }}<br>
                      Requested Quantity: {{ fuel.fuel_requested }}<br>
                      Fuel Issued: {{ fuel.fuel_issued }}<br>
                      Total Fuel in Tank: {{ fuel.fuel_total }}<br>
                      <hr>
                      <strong>Narration</strong><br>
                      {{ fuel.narration }}
                    </div>

                    <div class="col-xs-3">
                      <strong>Mileage Readings</strong><br>
                      Previous KM: {{ fuel.previous_km }}<br>
                      Previous Fuel: {{ fuel.previous_fuel }}<br>
                      Current Km: {{ fuel.current_km }}<br>
                      KM Covered: {{ km_covered }}<br>
                      Fuel Used: {{ fuel_used }}<br>
                      KM/Ltr: {{ km_per_litre }}<br>
                    </div>

                  </div>
                  <div class="form-group pull-right">
                    <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-success" @click="approveFuel(fuel.id)">Approve</button>
                    <button type="button" name="button" v-else class="btn btn-warn btn-warning" @click="approveFuel(fuel.id)">Cancel Approval</button>
                    <button type="button" class="btn btn-info" @click="printFuelVoucher"><i class="fa fa-print fa-fw"></i> Print</button>
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
                this.selectJourney();
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
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                    journey_id: '',
                    date: '',
                    current_fuel: 0,
                    fuel_requested: 0,
                    fuel_issued: 0,
                    fuel_total: 0,
                    narration: '',
                    previous_km: 0,
                    previous_fuel: 0,
                    current_km: 0,
                    status: 'Pending Approval'
                }
            };
        },
        computed: {

          minimumKm () {
            return this.fuel.previous_km;
          }
        },

        methods: {
          selectJourney() {
            let journey = this.journeys.filter(e => e.id == this.fuel.journey_id);
            if (journey.length) {
              this.current_driver = JSON.parse(JSON.stringify(journey[0].driver));
              this.current_vehicle = JSON.parse(JSON.stringify(journey[0].truck));
              this.current_trailer = JSON.parse(JSON.stringify(journey[0].truck.trailer));
              this.current_route = JSON.parse(JSON.stringify(journey[0].route));

              return this.current_journey = JSON.parse(JSON.stringify(journey[0]));
            }
          },
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
                          this.selectJourney();
                          this.setupUI();
                      });
              }
              this.selectJourney();
              this.setupUI();
          },
          calculateTotal() {
            return this.fuel.fuel_total = parseInt(this.fuel.fuel_issued) + parseInt(this.fuel.current_fuel);
          },

          calculateKms() {
            if(this.fuel.current_km < this.fuel.previous_km) {
              return alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
            }
            this.fuel_used = parseInt(this.fuel.previous_fuel) - parseInt(this.fuel.current_fuel);
            this.km_covered = parseInt(this.fuel.current_km) - parseInt(this.fuel.previous_km);

            return this.km_per_litre = parseInt(this.km_covered)/parseInt(this.fuel_used);
          },
          getSource() {
            if(this.current_driver.avatar){
              return '/images/'+this.current_driver.avatar;
            }
            return '/images/default_avatar.png';
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
            printFuelVoucher() {
              window.print('template');
            },
            approveFuel(id) {
              http.get('/api/approve/' + id).then(response => {
                if (response.status != 'success') {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'danger');
                    return;
                }

                this.fuel = response.fuel;

                this.$root.isLoading = false;
                alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                this.$root.isLoading = false;
                alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
            },
        }
    }
</script>
