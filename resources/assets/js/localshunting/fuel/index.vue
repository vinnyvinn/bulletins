<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks Awaiting Fueling</strong>
                        <button v-if="$root.can('ls-view-fuel')" type="button" class="btn btn-sm btn-success pull-right" name="button" @click="viewFuelsIndex()">FUEL ALLOCATED</button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th>Current Ltrs</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="truck in vehicles">
                                    <td>{{ truck.plate_number }}</td>
                                    <td v-if="truck.trailer">{{ truck.trailer.plate_number }}</td>
                                    <td v-if="!truck.trailer"> -- </td>
                                    <td v-if="truck.driver">{{ truck.driver.first_name }}</td>
                                    <td v-if="!truck.driver">--</td>
                                    <td>{{ truck.current_fuel}}</td>
                                    <td><button v-if="$root.can('ls-create-fuel')" type="button" @click="fuel(truck)" name="button" class="btn btn-sm btn-success">Fuel</button></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Plate #</th>
                                  <th>Trailer</th>
                                  <th>Driver</th>
                                  <th>Current Ltrs</th>
                                  <th>Action</th>
                                </tr>
                                </tfoot>
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
                vehicles: [],
                contract: {
                  client: {}
                },
            };
        },

        created() {
          this.$root.isLoading = true;
          http.get('/api/lsfuel?contract=' + window.Laravel.contract_id).then( response => {
            this.vehicles = response.vehicles;
            prepareTable();
            this.$root.isLoading = false;
          });
        },

        methods: {

            viewFuelsIndex(){
              this.$router.push('/ls/fuelindex');
            },

            fuel (truck) {
              this.$router.push('/ls/fuel/create/' + truck.id + '/' + truck.contract_id);
            },


            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },
        }
    }
</script>
