<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body" id="printable">

                <div class="row print-row" >
                  <div class="col-xs-12">
                    <h4><strong>FUEL VOUCHER</strong></h4>
                  </div>
                  <div class="col-xs-3">
                    <strong>Driver</strong><br>
                    <img :src="getSource()" alt="" width="100" height="100"> <br>
                    Name: {{ lsfuel.vehicle.driver.first_name  }}<br>
                    Id No: {{ lsfuel.vehicle.driver.identification_number }}<br>
                    Mobile No: {{ lsfuel.vehicle.driver.mobile_phone }}<br>
                    DL number: {{ lsfuel.vehicle.driver.dl_number }}
                  </div>

                  <div class="col-xs-3">
                      Date: {{ lsfuel.created_at }}<br>
                      Status: {{ lsfuel.status }}
                      <hr>
                      <strong>Route</strong>
                      Route: Route: RT-<br>
                      From: <br>
                      To: <br>
                      <hr>
                      <strong>Vehicle</strong>
                      Reg.No: {{ lsfuel.vehicle.plate_number }}<br>
                      Model: {{ lsfuel.vehicle.model }}<br>
                  </div>

                    <div class="col-xs-3">
                      <strong>Fuel</strong><br>
                      Standard Quantity for this route (Ltrs): ---<br>
                      Current Fuel (Litres): ---<br>
                      Requested Quantity: ---<br>
                      Fuel Issued: {{ lsfuel.fuel_issued }}<br>
                      Total Fuel in Tank: {{ lsfuel.total_in_tank }}<br>
                      <hr>
                      <strong>Narration</strong><br>
                      {{ lsfuel.narration }}
                      <hr>
                    </div>

                    <div class="col-xs-3">
                      <strong>Mileage Readings</strong><br>
                      Current Km: {{ lsfuel.current_km }}<br>
                    </div>

                  </div>
            <hr class="print-hr">
        </div>


        <div class="form-group pull-right">
          <button type="button" name="button" v-if="lsfuel.status == 'Pending Approval'" class="btn btn-success" @click="approveFuel(lsfuel.id)">Approve</button>
          <!-- <button type="button" name="button" v-else class="btn btn-warn btn-warning" @click="approveFuel(fuel.id)">Cancel Approval</button> -->
          <button type="button" class="btn btn-success" @click="printFuelVoucher" :disabled="disablePrint"><i class="fa fa-print fa-fw"></i> Print</button>
          <router-link to="/ls/fuelindex" class="btn btn-danger">Back</router-link>  
      </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                lsfuel: {
                  vehicle: {
                    driver: {}
                  }
                }
            };
        },

        created() {
            if (! this.$root.can('view-fuel')) {
                this.$router.push('/403');
                return false;
            }
          this.$root.isLoading = true;
            http.get('/api/lsfuel/' + this.$route.params.id).then((response) => {
                this.lsfuel = response.lsfuel;
                this.$root.isLoading = false;
            });
        },

        computed: {
          disablePrint(){
            if(this.lsfuel.status == "Approved"){
              return false;
            } else {
              return true;
            }
          },

        },

        methods: {
          calculateTotal() {
            return this.fuel.fuel_total = parseInt(this.fuel.fuel_issued) + parseInt(this.fuel.current_fuel);
          },

          getSource() {
            if(this.lsfuel.vehicle.driver.avatar){
              return '/images/'+this.vehicle.driver.avatar;
            }
            return '/images/default_avatar.png';
          },

            printFuelVoucher() {
              window.print();
            },

            approveFuel(id) {
              this.$root.isLoading = true;
              http.get('/api/ls/approvefuel/' + id).then(response => {
                if (response.status != 'success') {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'danger');
                    return;
                }

                this.lsfuel = response.lsfuel;

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
