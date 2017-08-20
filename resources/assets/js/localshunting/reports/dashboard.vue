<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Reports: Client:
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                    <label for="contract_id">Contract</label>
                    <select @change="fetchContract" v-model="contract_id" class="form-control input-sm" id="contract_id" required>
                        <option v-for="contract in contracts" :value="contract.id">CNTR{{ contract.id }} - ({{ contract.name }}) - {{ contract.client.Name }}</option>
                    </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="progress"> </label>
                  <button type="button" name="button" class="form-control btn btn-success input-sm" id="progress">View Progress</button>
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label for="summary"> </label>
                  <button type="button" name="button" class="form-control btn btn-primary input-sm" id="summary">View Summary</button>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Pending Offloading:
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>DNote No</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(delivery, index) in contract.lsdeliveries">
                            <td>{{ index + 1 }}</td>
                            <td>RKS-{{ delivery.id}}</td>
                            <td>{{ delivery.vehicle.plate_number }}</td>
                            <td>{{ delivery.vehicle.driver.first_name }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    Fuel Issue: Total Issued:
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Driver</th>
                            <th>Fuel Amt</th>
                            <th>Deliveries </th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(fuel, index) in contract.lsfuels">
                            <td>{{ index + 1 }}</td>
                            <td>{{ fuel.vehicle.plate_number }}</td>
                            <td>{{ fuel.vehicle.driver.first_name }}</td>
                            <td>{{ fuel.fuel_issued }}</td>
                            <td>Deliveries</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
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
        contracts: [],
        contract_id: [],
        contract: {}
      }
    },

    created () {
      this.$root.isLoading = true;
      http.get('/api/lsreport').then( (response) => {
        this.contracts = response.contracts;
        this.$root.isLoading = false;
      })
    },

    methods: {
      fetchContract () {
        this.$root.isLoading = true;
        http.get('/api/lsreport/' + this.contract_id).then( (response) => {
          this.contract = response.contract;
          this.$root.isLoading = false;
        });
      }
    }
}
</script>

<style lang="css">
</style>
