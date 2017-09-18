<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            Reports:
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                    <label for="contract_id">Select Contract</label>
                    <select @change="fetchContract" v-model="contract_id" class="form-control input-sm" id="contract_id" required>
                        <option v-for="contract in contracts" :value="contract.id">CNTR{{ contract.id }} - ({{ contract.name }}) - {{ contract.client.Name }}</option>
                    </select>
                </div>
              </div>
              <div class="col-sm-4" v-if="contract_id">
                <div class="form-group">
                  <label for="progress"> </label>
                  <button type="button" name="button" class="form-control btn btn-success input-sm" id="progress" @click="viewProgress()">View Progress</button>
                </div>
              </div>

              <div class="col-sm-4" v-if="contract_id">
                <div class="form-group">
                  <label for="summary"> </label>
                  <button type="button" name="button" class="form-control btn btn-primary input-sm" id="summary">View Summary</button>
                </div>
              </div>
            </div>
            <div class="row" v-if="contract_id">
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
                          <tr v-for="(delivery, index) in contract.lsdeliveries" v-if="delivery.status == 'Loaded'">
                            <td>{{ index + 1 }}</td>
                            <td>RKS-{{ delivery.id}}</td>
                            <td>{{ delivery.vehicle.plate_number }}</td>
                            <td>
                              <span v-if="delivery.temporary_driver">{{ delivery.temporary_driver.first_name }} {{ delivery.temporary_driver.last_name }}</span>
                              <span v-else-if="delivery.vehicle.driver">{{ delivery.vehicle.driver.first_name }} {{ delivery.vehicle.driver.last_name }}</span>
                          </td>
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
                    Fuel Issued
                    <b class="pull-right" v-if="contract.lsfuels">
                      Total Issued: {{ myarraySum(this.contract.lsfuels, 'fuel_issued') }} Ltrs</b>
                  </div>
                  <div class="panel-body">
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Refuels</th>
                            <th>Fuel Amt</th>
                            <th>Deliveries</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(fuel, index) in fuelsgrouped">
                            <td>{{ index + 1 }}</td>
                            <td>{{ fuel.vehicle }}</td>
                            <td>{{ fuel.fuel_issued.length }}</td>
                            <td>{{ myarraySum(fuel.fuel_issued, 'fuel') }}</td>
                            <td>{{ countInstances(fuel.vehicle) }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row" v-if="contract_id">
              <div class="col-sm-6">
                <div class="panel panel-default">
                  <div class="panel-heading">
                      Trucks Awaiting Loading
                  </div>
                  <div class="panel-body">
                    <table class="table no-wrap">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Plate #</th>
                          <th>Gate Pass #</th>
                          <th>Time In</th>
                          <th>Mins</th>
                          <th>Driver</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(gatepass, index) in contract.lsgatepasses">
                          <td>{{ index + 1 }}</td>
                          <td @click="openVehicle()">{{ gatepass.vehicle.plate_number }}</td>
                          <td>GP - {{ gatepass.id}}</td>
                          <td>{{ humanDate(gatepass.created_at) }}</td>
                          <td>{{ minsWaiting(gatepass.created_at) }}</td>
                          <td v-if="gatepass.vehicle.driver">{{ gatepass.vehicle.driver.first_name }} {{ gatepass.vehicle.driver.last_name }}</td>
                          <td v-if="!gatepass.vehicle.driver"> -- </td>
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
</template>

<script>

  export default {
    data() {
      return {
        contracts: [],
        contract_id: '',
        contract: {},
        fuelsgrouped: [],
      }
    },

    created() {
      this.$root.isLoading = true;
      http.get('/api/lsreport').then((response) => {
        this.contracts = response.contracts;

        this.$root.isLoading = false;
      })
    },

    methods: {
      fetchContract() {
        this.$root.isLoading = true;
        http.get('/api/lsreport/' + this.contract_id).then((response) => {
          this.contract = response.contract;
          this.groupArrayObjects(this.contract.lsfuels);
          this.$root.isLoading = false;
        });
      },

      humanDate(created_at) {
        return moment(created_at).format('ll');
      },

      minsWaiting(created_at) {
        return moment.duration(moment().diff(moment(created_at))).humanize();
      },

      myarraySum(items, prop) {
        return items.reduce(function(a, b) {
          var b = parseInt(b[prop]);
          return a + b;
        }, 0);
      },

      groupArrayObjects(myArray) {
        var groups = {};
        for (var i = 0; i < myArray.length; i++) {
          if (! myArray[i].vehicle) continue;
          var groupName = myArray[i].vehicle.plate_number;
          if (!groups[groupName]) {
            groups[groupName] = [];
          }
          groups[groupName].push({ 'fuel': myArray[i].fuel_issued });
        }
        myArray = [];
        for (var groupName in groups) {
          myArray.push({ vehicle: groupName, fuel_issued: groups[groupName] });
        }
        this.fuelsgrouped = myArray;
      },

      countInstances(value) {
        var count = 0;
        var deliveries = this.contract.lsdeliveries
        for (var i = 0; i < deliveries.length; i++) {
          if (deliveries[i].vehicle.plate_number == value) {
            count = count + 1;
          }
        }
        return count;
      },

      openVehicle() {
        alert('Hi');
      },

      viewProgress() {
        this.$router.push('/ls/loadingreports/' + this.contract_id);
      }


    }
  }
</script>

<style lang="css" scoped>
table {
  height: 150px !important;
}
</style>
