<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Loadings and offloadings - Local Shunting
          </div>
          <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                  Contract No: <b class="pull-right">CTR - {{ this.$route.params.id }}</b>
                </div>
                <div class="col-sm-3">
                  Client Name: <b class="pull-right">{{ contract.client.Name }}</b>
                </div>
                <div class="col-sm-3">
                  Contract Status:
                  <b class="pull-right">
                    <span class="label label-info" v-if="contract.status == 'Pending Approval'">{{ contract.status }}</span>
                    <span class="label label-success" v-if="contract.status == 'Approved'">{{ contract.status }}</span>
                    <span class="label label-default" v-if="contract.status == 'Closed'">{{ contract.status }}</span>
                  </b>
                </div>
                <div class="col-sm-3">
                  Trucks: <b class="pull-right">{{ contract.trucks_allocated }}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Route No: <b class="pull-right">{{ contract.route_id }}</b>
                </div>
                <div class="col-sm-3">
                  From: <b class="pull-right">{{ contract.route.source }}</b>
                </div>
                <div class="col-sm-3">
                  To: <b class="pull-right">{{ contract.route.destination }}</b>
                </div>
                <div class="col-sm-3">
                  Shifts: <b class="pull-right">{{ contract.no_of_shifts }}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Contract Allocated: <b class="pull-right">{{ parseFloat(contract.quantity).toLocaleString() }}</b>
                </div>
                <div class="col-sm-3">
                  Total Offloaded: <b class="pull-right">{{ parseFloat(myarraySum(lsdeliveries, 'offloading_net_weight')).toLocaleString() }}</b>
                </div>
                <div class="col-sm-3">
                  Pending Offloading: <b class="pull-right">{{ parseFloat(parseFloat(contract.quantity) - myarraySum(lsdeliveries, 'offloading_net_weight')).toLocaleString()  }}</b>
                </div>
                <div class="col-sm-3">
                  <div class="progress">
                    <div class="progress-bar progress-bar-success" role="progressbar" :aria-valuenow="progress()"
                    aria-valuemin="0" aria-valuemax="150" :style="styleProgress()">
                      {{ progress() }} % Complete
                    </div>
                  </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Start Date: <b class="pull-right">{{ humanDate(contract.start_date) }}</b>
                </div>
                <div class="col-sm-3">
                  Days: <b class="pull-right">{{ daysSince(contract.start_date) }}</b>
                </div>
            </div>
            <hr>
                  <div class="table responsive">
                    <table class="table table-nowrap">
                      <thead>
                        <tr>
                          <th>SNo</th>
                          <th>Truck</th>
                          <th>DNote</th>
                          <th>Station</th>
                          <th class="text-right">Load QTY</th>
                          <th class="text-right">Offload QTY</th>
                          <th>Driver</th>
                          <th class="text-right">WB Loading</th>
                          <th class="text-right">WB Offloading</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(delivery, index) in lsdeliveries">
                          <td>{{ index + 1 }}</td>
                          <td>
                            <span v-if="delivery.vehicle">{{ delivery.vehicle.plate_number }}</span>
                          </td>
                          <td>RKS - {{ delivery.id }}</td>
                          <td><span v-if="delivery.station">{{ delivery.station.name }}</span></td>
                          <td class="text-right">{{ delivery.loading_net_weight }}</td>
                          <td class="text-right">{{ delivery.offloading_net_weight }}</td>
                          <td>
                              <span v-if="delivery.temporary_driver">{{ delivery.temporary_driver.first_name }} {{ delivery.temporary_driver.last_name }}</span>
                              <span v-else-if="delivery.vehicle.driver">{{ delivery.vehicle.driver.first_name }} {{ delivery.vehicle.driver.last_name }}</span>
                          </td>
                          <td class="text-right">{{ delivery.loading_weighbridge_number }}</td>
                          <td class="text-right">{{ delivery.offloading_weighbridge_number }}</td>
                          <td>{{ humanDate(delivery.created_at) }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        lsdeliveries: [],
        contract: {
          client: {
            Name: ""
          },
          route: {}
        }
      };
    },
    created() {
      this.$root.isLoading = true;
      http
        .get("/api/lsloadingunloading/" + this.$route.params.id)
        .then(response => {
          this.lsdeliveries = response.lsdeliveries;
          this.contract = response.contract;
          prepareTable();
          this.$root.isLoading = false;
        });
    },

    methods: {
      humanDate(date) {
        return moment(date).format("ll");
      },

      daysSince(startdate) {
        return moment().diff(moment(startdate), "days");
      },

      myarraySum(items, prop) {
        return items.reduce(function(a, b) {
          var b = parseInt(b[prop]);
          b = isNaN(b) ? 0 : b;

          return a + b;
        }, 0);
      },

      progress() {
        return parseFloat(
          this.myarraySum(this.lsdeliveries, "offloading_net_weight") /
            this.contract.quantity *
            100
        ).toLocaleString();
      },

      styleProgress() {
        return "width:" + this.progress() + "%";
      }
    }
  };
</script>

<style lang="css">

</style>
