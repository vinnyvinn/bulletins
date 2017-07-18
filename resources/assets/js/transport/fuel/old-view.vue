<template>
    <div class="panel panel-default">
        <div class="panel-heading hidden-print">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body" id="printable">
            <div class="row">
                <div class="col-xs-6">
                    <h3>
                        <img style='display:block' src="/images/logo.jpg" alt="Sanghani">
                        <strong>FUEL VOUCHER</strong>
                        <span> <small>TOP UP</small></span>
                    </h3>
                </div>
                <div class="col-xs-6">
                    <div class="text-right">
                        <h3>{{ config.name }}</h3>
                        <h4>{{ config.telephone }}</h4>
                        <h4>{{ config.email }}</h4>
                        <h4>{{ config.location }}</h4>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher No: </strong> FUEL-{{ fuel.id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Truck No: </strong> <span class="text-uppercase">{{ fuel.journey.truck.plate_number }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Date to be issued: </strong> {{ fuel.date }}
                    </div>
                </div>



                <div class="row">
                    <div class="col-xs-4">
                        <strong>Journey No: </strong> RKS-{{ delivery_note.id }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Route: </strong> <span class="text-uppercase">{{ fuel.journey.route.source }}</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>To: </strong> <span class="text-uppercase">{{ fuel.journey.route.destination }}</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Driver: </strong> {{ fuel.journey.driver ? fuel.journey.driver.first_name : '' }} {{ fuel.journey.driver ? fuel.journey.driver.last_name : '' }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Distance: </strong> <span class="text-uppercase">{{ fuel.journey.distance }} KMs</span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Fuel Qty: </strong> <span class="text-uppercase">{{ fuel.fuel_issued }} Litres</span>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-12">
                        <strong>Supervisors Comment</strong>
                        <br>
                        <p>{{ fuel.narration }}</p>
                    </div>
                </div>

                <br>
                <br>

                <div class="row">
                    <div class="col-xs-4">
                        <strong>Voucher Approved By:</strong>
                        <br>
                        <span v-if="fuel.user">
                                        {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                                    </span>
                    </div>
                    <div class="col-xs-4">
                        <strong>Voucher Processed By: </strong>
                        <br>
                        {{ fuel.user.first_name }} {{ fuel.user.last_name }}
                    </div>
                    <div class="col-xs-4">
                        <strong>Recipient Sign: </strong>
                        <br>
                        .....................................................................
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <img class="center-block" src="/images/logo.jpg" alt="Sanghani">
                    <h4 class="text-center"><strong>FUEL VOUCHER (Delivery Note N0: RKS {{ delivery_note.id }})</strong></h4>
                </div>
                <div class="col-xs-3">
                    <h4><strong>Driver</strong></h4>
                    <img class="center-block" :src="getSource()" alt="" width="100" height="100">
                    <h5><strong>Name:</strong> {{ fuel.journey.driver.first_name  }}</h5>
                    <h5><strong>ID No:</strong> {{ fuel.journey.driver.identification_number }}</h5>
                    <h5><strong>Mobile No:</strong> {{ fuel.journey.driver.mobile_phone }}</h5>
                    <h5><strong>DL number:</strong> {{ fuel.journey.driver.dl_number }}</h5>
                </div>
                <div class="col-xs-3">
                    Date: {{ fuel.date }}<br>
                    Journey: JRNY-{{ fuel.journey_id }}<br>
                    Status: {{ fuel.status }}
                    <hr>
                    <strong>Route</strong>
                    Route: Route: RT-{{ fuel.journey.route.id}}<br>
                    From: {{ fuel.journey.route.source }}<br>
                    To: {{ fuel.journey.route.destination }}<br>
                    <hr>
                    <strong>Vehicle</strong>
                    Reg.No: {{ fuel.journey.truck.plate_number }}<br>
                    Model: {{ fuel.journey.truck.model}}<br>
                    Trailer Attached: <input type="checkbox" disabled name="trailer_attached" :checked="fuel.journey.truck.trailer_id"><br>
                    <div class="" v-if="fuel.journey.truck.trailer_id">
                        Trailer: {{ fuel.journey.truck.trailer_id }}<br>
                        Trailer Category: {{ fuel.journey.truck.trailer_id }} <br>
                    </div>
                </div>
                <div class="col-xs-3">
                    <strong>Fuel</strong><br>
                    Standard Quantity for this route (Ltrs): {{ fuel.fuel_required }}<br>
                    Current Fuel (Litres): {{ fuel.current_fuel }}<br>
                    Requested Quantity: {{ fuel.fuel_requested }}<br>
                    Fuel Issued: {{ fuel.fuel_issued }}<br>
                    Total Fuel in Tank: {{ fuel.fuel_total }}<br>
                    <hr>
                    <strong>Narration</strong><br>
                    {{ fuel.narration }}
                    <hr>
                    <strong>Top Up fuel</strong>
                    <div class="col-xs-12" v-if="parseInt(fuel.top_up)">
                        Top Up: {{ fuel.top_up_quantity}}<br>
                        Reason: {{ fuel.top_up_reason }}
                    </div>
                    <hr>
                </div>
                <div class="col-xs-3">
                    <strong>Mileage Readings</strong><br>
                    Current Km: {{ fuel.current_km }}<br>
                    Previous KM: {{ fuel.previous_km }}<br>
                    <strong>KM Covered: {{ parseInt(fuel.current_km)-parseInt(fuel.previous_km) }} Kms<br></strong>
                    Current Fuel (Litres): {{ fuel.current_fuel }}<br>
                    Previous Fuel: {{ fuel.previous_fuel }}<br>
                    <strong>Fuel Used: {{ parseInt(fuel.previous_fuel)-parseInt(fuel.current_fuel) }} Ltrs<br></strong>
                    <!--<strong>-->
                        <!--KM/Ltr: {{ parseInt((parseInt(fuel.current_km)-parseInt(fuel.previous_km))/parseInt(parseInt(fuel.previous_fuel)-parseInt(fuel.current_fuel))).toFixed(2) }}<br>-->
                    <!--</strong>-->
                </div>
            </div>

            <hr class="print-hr">
            <div class="row print-row">
              <div class="col-xs-12">
                <h4><strong>MILEAGE ALLOCATION (Delivery Note N0: RKS {{ delivery_note.id }})</strong></h4>
              </div>
              <div class="col-xs-3">
                Journey Number: {{ mileage.journey_id }}<br>
                Mileage Type: {{ mileage.mileage_type }}<br>
                Requested Amount: {{ mileage.requested_amount }}<br>
                Standard Amount: {{ mileage.standard_amount }}<br>
              </div>
              <div class="col-xs-3" v-if="parseInt(mileage.top_up)">
                <strong>Top Up</strong><br>
                Amount: {{ mileage.top_up_amount }}<br>
                Reason: {{mileage.top_up_reason }}
              </div>

              <div class="col-xs-3">
                Mileage Allocation No: {{ mileage.id }}<br>
                Status: {{ mileage.status }}<br>
                Amount Approved: {{ mileage.approved_amount }}<br>
              </div>

              <div class="col-xs-3">
                Narration:<br>
                {{ mileage.narration }}
              </div>
            </div>
        </div>


        <div class="form-group pull-right hidden-print">
          <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-success" @click="approveFuel(fuel.id)">Approve</button>
          <!-- <button type="button" name="button" v-else class="btn btn-warn btn-warning" @click="approveFuel(fuel.id)">Cancel Approval</button> -->
          <button type="button" class="btn btn-success" @click="printFuelVoucher" :disabled="disablePrint"><i class="fa fa-print fa-fw"></i> Print</button>
          <router-link to="/fuel" class="btn btn-danger">Back</router-link>
      </div>
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        data() {
            return {
                config: {},
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                  journey: {
                    driver: {},
                    route: {},
                    truck: {},
                  },
                    user: {},
                  fuel_required: '',
                  current_fuel: '',
                  fuel_requested: '',
                  fuel_issued: '',
                  fuel_total: '',
                  narration: '',
                  tank: '',
                  pump: '',
                  top_up: '',
                  top_up_reason: '',
                  top_up_quantity: 0,
                },
                delivery_note: '',
                mileage: {
                  journey_id: '',
                  mileage_type: '',
                  requested_amount: '',
                  standard_amount: '',
                  id: '',
                  status: '',
                  approved_amount: '',
                  narration: '',
                  top_up: '',
                  top_up_reason: '',
                  top_up_amount: 0,
                }
            };
        },
        created() {
            if (! this.$root.can('view-fuel')) {
                this.$router.push('/403');
                return false;
            }
          this.$root.isLoading = true;
            http.get('/api/fuel/' + this.$route.params.id).then((response) => {
                this.fuel = response.fuel;
                this.delivery_note = response.delivery_note;
                this.mileage = response.mileage;
                this.config = response.config;
                this.$root.isLoading = false;
            });
        },
        computed: {
          disablePrint(){
            if(this.fuel.status == "Approved" && this.mileage.status == "Approved"){
              return false;
            }
            else{
              return true;
            }
          },

          minimumKm () {
            return this.fuel.previous_km;
          }
        },

        methods: {
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
            if(this.fuel.journey.driver.avatar){
              return '/images/'+this.journey.driver.avatar;
            }
            return '/images/default_avatar.png';
          },
            printFuelVoucher() {
              window.print();
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
