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
                  Contract No. CTR - {{ this.$route.params.id }}
                </div>
                <div class="col-sm-3">
                  Client Name: {{ contract.client.Name }}
                </div>
                <div class="col-sm-3">
                  Contract Status: {{ contract.status }}
                </div>
                <div class="col-sm-3">
                  Trucks: {{ contract.trucks_allocated }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Route No: {{ contract.route_id }}
                </div>
                <div class="col-sm-3">
                  From: {{ contract.route.source }}
                </div>
                <div class="col-sm-3">
                  To: {{ contract.route.destination }}
                </div>
                <div class="col-sm-3">
                  Shifts: {{ contract.shifts }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Contract Allocated: {{ contract.quantity }}
                </div>
                <div class="col-sm-3">
                  Total Offloaded: offloaded
                </div>
                <div class="col-sm-3">
                  Pending Offloading:
                </div>
                <div class="col-sm-3">
                  % Completion:
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                  Start Date: {{ contract.start_date }}
                </div>
                <div class="col-sm-3">
                  Days: {{  }}
                </div>
                <div class="col-sm-3">
                  Offloaded:
                </div>
                <div class="col-sm-3">
                  Loaded:
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
                          <th>Load QTY</th>
                          <th>Offload QTY</th>
                          <th>Driver</th>
                          <th>WB Loading</th>
                          <th>Date</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(delivery, index) in lsdeliveries">
                          <td>{{ index + 1 }}</td>
                          <td>{{ delivery.vehicle.plate_number }}</td>
                          <td>RKS - {{ delivery.id }}</td>
                          <td>{{ delivery.loading_net_weight }}</td>
                          <td>{{ delivery.offloading_net_weight }}</td>
                          <td>{{ delivery.vehicle.driver.first_name}} {{ delivery.vehicle.driver.last_name}}</td>
                          <td>{{ delivery.loading_weighbridge_number }}</td>
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
          Name: '',
        },
        route: {
          
        }
      },
    }
  },
  created() {
    this.$root.isLoading = true;
    http.get('/api/lsloadingunloading/' + this.$route.params.id).then( (response) => {
      this.lsdeliveries = response.lsdeliveries;
      this.contract = response.contract;
      prepareTable();
      this.$root.isLoading = false;
    })
  },

  methods: {
    humanDate(date) {
      return moment(date).format('ll');
    },
  }


}
</script>

<style lang="css">
</style>
