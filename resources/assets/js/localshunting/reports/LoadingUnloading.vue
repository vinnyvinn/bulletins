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
              <div class="col-sm-12">
                <div class="col-sm-3">
                  Contract No. CTR - {{ this.$route.params.id }}
                </div>
                <div class="col-sm-4">
                  Client Name: {{ contract.client.Name }}
                </div>
                <div class="col-sm-">
                  Contract Status: {{ contract.status }}
                </div>
              </div>
            </div>
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
    reRender() {

    }
  }


}
</script>

<style lang="css">
</style>
