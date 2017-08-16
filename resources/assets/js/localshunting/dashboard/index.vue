<template>
    <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Contracts
              </div>
              <div class="panel-body">
                Contracts: {{ contracts.length }}
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Allocation
              </div>
              <div class="panel-body">

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

              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                GatePass
              </div>
              <div class="panel-body">

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

            this.$root.isLoading = false;
          });
        },

        methods: {

        }
    }
</script>
