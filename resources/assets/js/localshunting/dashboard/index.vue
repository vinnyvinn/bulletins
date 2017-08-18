<template>
    <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                Contracts
              </div>
              <div class="panel-body">
                Contracts: {{ contracts.length }}<br>
                <span class="label label-warning">Pending Approval: {{ pending_approval }}</span>
                <span class="label label-success">Approved: {{ approved }}</span>
                <span class="label label-default">Closed: {{ closed }}</span>

              <table class="table">
                <thead>
                  <tr>
                    <th>Contract</th>
                    <th>Supervisors</th>
                    <th>Casuals</th>
                    <th>Fuel</th>
                    <th>gatepasses</th>
                    <th>Deliveries</th>
                    <th>Mileages</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="contract in contracts">
                    <td>{{ contract.id }}</td>
                    <td>{{ }}</td>
                    <td>{{ }}</td>
                    <td>{{ contract.lsfuels.length }}</td>
                    <td>{{ contract.lsgatepasses.length }}</td>
                    <td>{{ contract.lsdeliveries.length }}</td>
                    <td>{{ contract.lsmileages.length }}</td>
                  </tr>
                </tbody>
              </table>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Fuel
              </div>
              <div class="panel-body">
                <ol>
                </ol>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                GatePass
              </div>
              <div class="panel-body">
                <ol>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Deliveries
              </div>
              <div class="panel-body">
                <ol>
                </ol>
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Mileages
              </div>
              <div class="panel-body">

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
                pending_approval: 0,
                approved: 0,
                closed: 0,
                contracts: [],
                allocations: [],
                fuels: [],
                gatepasses: [],
                deliveries: [],
                mileages: [],
            }
        },

        created() {
          this.$root.isLoading = true;
          http.get('/api/lsdashboard').then(response => {
            this.contracts = response.contracts;
            this.fuels = response.fuels;
            this.gatepasses = response.gatepasses;
            this.deliveries = response.deliveries;
            this.mileages = response.mileages;

            this.contractSummary();

            this.$root.isLoading = false;
          });
        },
        computed: {

        },

        methods: {
          contractSummary() {
            let i = 0;

            for(i; i < this.contracts.length; i++) {
              if(this.contracts[i].status == 'Pending Approval'){
                this.pending_approval += 1;
              } else if(this.contracts[i].status == 'Approved') {
                this.approved += 1;
              } else if(this.contracts[i].status == 'Closed') {
                this.closed += 1;
              }
            }
          },

          contractFuel(contract){
            contractFuel = 0;
            for(let i=0; i<contract.lsfuel.length; i++) {
              contractFuel += contract.lsfuel.fuel_issued;
            }
            return contractFuel;
          }
        }
    }
</script>
