<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation -- Truck: {{ truck.plate_number }} </strong>
            <strong class="pull-right">Contract Average Trips: {{ contract_settings.trips_before_refuel }}</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="previous_km">Previous KM</label>
                        <input disabled type="number" name="previous_km" class="form-control input-sm" v-model="truck.current_km">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="current_km">Current KM</label>
                        <input type="number" :min="minimumKm" name="current_km"  class="form-control input-sm" v-model="fuel.current_km" @change="validateKm">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="current_fuel">Balance in Tank (Litres)</label>
                        <input type="number" min=0 :max="refuelAmount()" name="current_fuel" class="form-control input-sm" v-model="fuel.current_fuel" @keyup="calculateFuelToIssue">
                      </div>
                    </div>

                  </div>

                  <div class="col-sm-4">

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="fuel_issued">Previous Fuel</label>
                        <input readonly type="text" name="previous_fuel" class="form-control input-sm" v-model="truck.current_fuel">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="fuel_issued">Total Fuel in Tank(Litres)</label>
                        <input readonly type="text" name="fuel_total" class="form-control input-sm" v-model="fuel.total_in_tank">
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="fuel_issued">Fuel To be Issued (Litres)</label>
                        <input readonly type="number" min=0 name="fuel_issued" class="form-control input-sm" v-model="fuel.fuel_issued">
                      </div>
                    </div>

                    Refuel: <strong>{{ refuelNumber() }}</strong><br>
                    Contract Set Fuel: <strong>{{ refuelAmount() }}</strong>

                  </div>

                  <div class="col-sm-4">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="fuel_issued">Trips since last refuel</label>
                        <input readonly type="number" name="fuel_issued" class="form-control input-sm" v-model="trips">
                      </div>
                    </div>

                    <div class="col-sm-12" v-if="!first_fuelling">
                      <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea name="reason" class="form-control input-sm" v-model="fuel.reason" placeholder="Reason why truck has made less trips"></textarea>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="narration">Description</label>
                        <textarea name="narration" class="form-control input-sm" v-model="fuel.narration"></textarea>
                      </div>
                    </div>

                    <div class="col-sm-12">
                      <div class="col-sm-12">
                        <i class="message">{{ message }}</i>
                      </div>

                      <div class="form-group pull-right">
                          <button class="btn btn-success" :disabled="!can_save">{{ refuelValidity() ? 'Save' : 'Request Approval' }}</button>
                          <router-link to="/ls/fuel" class="btn btn-danger">Back</router-link>
                      </div>
                    </div>

                  </div>

                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                trips: 0,
                truck: {},
                fuel_reserve: 25,
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                    station_id: window.Laravel.station_id,
                    contract_id: '',
                    vehicle_id: '',
                    current_fuel: 0,
                    fuel_issued: 0,
                    total_in_tank: 0,
                    narration: '',
                    previous_km: 0,
                    previous_fuel: 0,
                    current_km: 0,
                    tank: '',
                    pump: '',
                    under_trips: 0,
                    reason: '',
                    refuel_number: 0,
                    needs_approval: false,
                },
                can_save: false,
                below_reserve: false,
                deficit: '',
                message: '',
                contract_settings: {
                  average_fuel_per_trip: 0,
                  trips_before_refuel: 0,
                  initial_fuel: 0,
                  refuel_1: 0,
                  refuel_2: 0,
                  refuel_3: 0
                },
                first_fuelling: false,
                previous_refuels: 0
            };
        },

        created() {
            if (! this.$route.params.id && ! this.$route.params.unload &&  ! this.$root.can('create-fuel')) {
                this.$router.push('/403');
                return false;
            }

            if (this.$route.params.id && ! this.$root.can('edit-fuel')) {
                this.$router.push('/403');
                return false;
            }

          this.$root.isLoading = true;
          this.fuel.vehicle_id = this.$route.params.id;
          this.fuel.contract_id = this.$route.params.contract;

          if(this.$route.params.id){
            this.can_save = true;
          }
            this.$root.isLoading = true;
            http.get('/api/lsfuelcreate/' + this.$route.params.id + '/' + this.$route.params.contract).then( (response) => {
                this.truck = response.truck;
                this.trips = response.trips;
                this.contract_settings = response.contract_settings;
                this.fuel.under_trips = parseInt(this.contract_settings.trips_before_refuel) - parseInt(this.trips);
                this.previous_refuels = response.previous_refuels;
                this.fuel.refuel_number = this.refuelNumber();
                this.refuelValidity();
                this.fuel.needs_approval = !this.refuelValidity();
                this.$root.isLoading = false;
            });
        },

        mounted() {
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        computed: {
          minimumKm () {
            return this.truck.current_km;
          },

        },

        methods: {
          refuelNumber() {
            if(this.previous_refuels == 0) {
              return 'first';
            } else if( this.previous_refuels == 1) {
              return 'second';
            } else if( this.previous_refuels == 2) {
              return 'third';
            } else {
              return 'other';
            }
          },

          refuelValidity() {
            if(this.refuelNumber == 'first') {
              return true;
            }

            if( parseInt(this.trips) < parseInt(this.contract_settings.trips_before_refuel) ) {
              this.message = 'Trips done are less than the average trips set. Refueling requires approval.';
              return false;
            } else {
              return true;
            }
          },

          validateKm () {
            if(parseFloat(this.fuel.current_km) < parseFloat(this.truck.current_km)){
              alert2(this.$root, ['Current Km should be more than previous Km'], 'danger');
              this.fuel.current_km = 0;
              return;
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
                          this.setupUI();
                          this.$root.isLoading = false;
                      });
              }
              this.setupUI();
          },
          calculateFuelToIssue() {
            if(this.refuelNumber() == 'first') {
              this.fuel.fuel_issued = this.contract_settings.initial_fuel - this.fuel.current_fuel;
              this.fuel.total_in_tank = this.contract_settings.initial_fuel;
            } else if (this.refuelNumber() == 'second') {
              this.fuel.fuel_issued = this.contract_settings.refuel_1 - this.fuel.current_fuel;
              this.fuel.total_in_tank = this.contract_settings.refuel_1;
            }else if (this.refuelNumber() == 'third') {
              this.fuel.fuel_issued = this.contract_settings.refuel_2 - this.fuel.current_fuel;
              this.fuel.total_in_tank = this.contract_settings.refuel_2;
            } else {
              this.fuel.fuel_issued = this.contract_settings.refuel_3 - this.fuel.current_fuel;
              this.fuel.total_in_tank = this.contract_settings.refuel_3;
            }
          },

          refuelAmount() {
            if(this.refuelNumber() == 'first') {
              return this.contract_settings.initial_fuel;
            } else if (this.refuelNumber() == 'second') {
              return this.contract_settings.refuel_1;
            }else if (this.refuelNumber() == 'third') {
              return this.contract_settings.refuel_2;
            } else {
              return this.contract_settings.refuel_3;
            }
          },

          calculateTotal() {
            let reserve = parseInt(this.fuel_reserve);
            let current_fuel = parseInt(this.fuel.current_fuel);
            let route_fuel_required = parseInt(this.current_route.fuel_required);

            if(parseInt(this.fuel.current_fuel) < reserve){
              this.deficit = reserve - current_fuel;
              this.below_reserve  = true;
            }else{
              this.below_reserve = false;
            }

            this.fuel.fuel_requested = route_fuel_required + reserve - current_fuel;
            return this.fuel.fuel_total = parseInt(this.fuel.fuel_issued) + parseInt(this.fuel.current_fuel);
          },

          calculateKms() {
            if(parseInt(this.fuel.current_km) < parseInt(this.current_vehicle.current_km)) {
              this.can_save = false;
              this.fuel.current_km = 0;
              return alert2(this.$root, ['Current Km readings should be greater than previous Km reading'], 'danger');
            }
            this.fuel_used = parseInt(this.current_vehicle.current_fuel) - parseInt(this.fuel.current_fuel);
            this.km_covered = parseInt(this.fuel.current_km) - parseInt(this.current_vehicle.current_km);

            if(parseInt(this.fuel.current_km) > 0 && parseInt(this.fuel.current_km) > parseInt(this.current_vehicle.current_km)) {
              this.can_save = true;
            }

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

                let  request = axios.post('/api/lsfuel', this.fuel)

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.data.message], 'success');

                    this.$router.push('/ls/fuel/' + this.fuel.vehicle_id);
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>

<style media="screen" scoped>
  .message{
    color: red;
  }
</style>
