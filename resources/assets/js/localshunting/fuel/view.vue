<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation ({{ lsfuel.status }})</strong>
        </div>

        <div class="panel-body" id="printable">

                <div class="row print-row" >
                  <div class="col-xs-12">
                    <h4><strong>FUEL VOUCHER</strong></h4>
                  </div>
                  <div class="col-xs-3">
                    <strong>Driver</strong><br>
                    <img :src="getSource()" alt="" width="100" height="100"> <br>
                    Name: <strong class="pull-right">{{ lsfuel.vehicle.driver.first_name  }}</strong><br>
                    Id No: <strong class="pull-right">{{ lsfuel.vehicle.driver.identification_number }}</strong><br>
                    Mobile No: <strong class="pull-right">{{ lsfuel.vehicle.driver.mobile_phone }}</strong><br>
                    DL Number: <strong class="pull-right">{{ lsfuel.vehicle.driver.dl_number }}</strong>
                  </div>

                  <div class="col-xs-3">
                      Date: <strong class="pull-right">{{ humanDate(lsfuel.created_at) }}</strong><br>
                      Reg.No: <strong class="pull-right">{{ lsfuel.vehicle.plate_number }}</strong><br>
                      Model: <strong class="pull-right">{{ lsfuel.vehicle.model }}</strong><br>
                      Current Km: <strong class="pull-right">{{ lsfuel.current_km }}</strong><br>
                      Route: <strong v-if="lsfuel.contract" class="pull-right"> RT-{{ lsfuel.contract.route_id }}</strong><br>
                      From: <strong v-if="lsfuel.contract.route" class="pull-right"> {{ lsfuel.contract.route.source }}</strong><br>
                      To: <strong v-if="lsfuel.contract.route" class="pull-right"> {{ lsfuel.contract.route.destination }}</strong><br>

                  </div>

                    <div class="col-xs-3">
                      <strong>Fuel</strong><br>
                      Standard Quantity for this route (Ltrs): <strong v-if="lsfuel.contract.contract_config" class="pull-right">{{ lsfuel.contract.contract_config.average_fuel_per_trip }}</strong><br>
                      Trips Made: <strong class="pull-right"> </strong><br>
                      Fuel Requested: <strong class="pull-right">{{ lsfuel.fuel_issued }} Ltrs </strong><br>
                    </div>

                  </div>
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
                  },
                  contract: {
                    route: {},
                    contract_config: {}
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
          humanDate(date) {
            return moment(date).format('ll');
          },

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
