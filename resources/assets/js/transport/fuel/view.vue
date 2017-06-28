<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body" id="printable">

                <div class="row print-row" >
                  <div class="col-xs-12">
                    <h4><strong>FUEL VOUCHER (Delivery Note N0: RKS {{ delivery_note.id }})</strong></h4>
                  </div>
                  <div class="col-xs-3">
                    <strong>Driver</strong><br>
                    <img :src="getSource()" alt="" width="100" height="100"> <br>
                    Name: {{ fuel.journey.driver.first_name  }}<br>
                    Id No: {{ fuel.journey.driver.identification_number }}<br>
                    Mobile No: {{ fuel.journey.driver.mobile_phone }}<br>
                    DL number: {{ fuel.journey.driver.dl_number }}
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
                      Trailer Attached: <input type="checkbox" name="trailer_attached" :checked="fuel.journey.truck.trailer_id"><br>
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
                    </div>
                    <div class="col-xs-3" v-if="fuel.top_up_quantity">
                      <strong>Top Up fuel</strong>
                      Top Up: {{ fuel.top_up_quantity }}<br>
                      Top Up reason: {{ fuel.top_up_reason}}
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


            <hr class="print-hr">
            <div class="row print-row">
              <div class="col-xs-12">
                <h4><strong>MILEAGE ALLOCATION (Delivery Note N0: RKS {{ delivery_note.id }})</strong></h4>
              </div>
              <div class="col-xs-4">
                Journey Number: {{ mileage.journey_id }}<br>
                Mileage Type: {{ mileage.mileage_type }}<br>
                Requested Amount: {{ mileage.requested_amount }}<br>
                Standard Amount: {{ mileage.standard_amount }}<br>
              </div>
              <div class="col-xs-3">
                Mileage Allocation No: {{ mileage.id }}<br>
                Status: {{ mileage.status }}<br>
                Amount Approved: {{ mileage.approved_amount }}<br>
              </div>

              <div class="col-xs-4">
                Narration:<br>
                {{ mileage.narration }}
              </div>
            </div>
        </div>


        <div class="form-group pull-right">
          <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-success" @click="approveFuel(fuel.id)">Approve</button>
          <button type="button" name="button" v-else class="btn btn-warn btn-warning" @click="approveFuel(fuel.id)">Cancel Approval</button>
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
                km_covered: 0,
                fuel_used: 0,
                km_per_litre: 0,
                fuel: {
                  journey: {
                    driver: {
                      first_name: '',
                      identification_number: '',
                      mobile_phone: '',
                      dl_number: '',
                      avatar: ''
                    },
                    route: {
                      id: '',
                      source: '',
                      destination: ''
                    },
                    truck: {
                      model: '',
                      trailer_id: ''
                    }
                  },
                  fuel_required: '',
                  current_fuel: '',
                  fuel_requested: '',
                  fuel_issued: '',
                  fuel_total: '',
                  narration: '',
                  tank: '',
                  pump: '',
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
                  narration: ''
                }
            };
        },
        created() {
            http.get('/api/fuel/' + this.$route.params.id).then((response) => {
                this.fuel = response.fuel;
                this.delivery_note = response.delivery_note;
                this.mileage = response.mileage;
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
