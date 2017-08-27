<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Local Shunting Fueling</strong>
                        <button type="button" class="btn btn-sm btn-success pull-right" name="button" @click="viewTrucksAwaitingFueling()">Trucks Awaiting Fueling</button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fuel Id</th>
                                    <th>Status</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Fuel</th>
                                    <th>Created By</th>
                                    <th>Date</th>
                                    <th>Needs Approval</th>
                                    <th>Approved By</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(lsfuel, index) in lsfuels">
                                  <th>{{ index + 1 }}</th>
                                  <td>LS Fuel - {{ lsfuel.id}}</td>
                                  <td>{{ lsfuel.status }}</td>
                                  <td>{{ lsfuel.vehicle.plate_number }}</td>
                                  <td>{{ lsfuel.vehicle.driver.first_name }} {{ lsfuel.vehicle.driver.last_name }}</td>
                                  <td>{{ lsfuel.fuel_issued }}</td>
                                  <td>{{ lsfuel.user.first_name }}</td>
                                  <td>{{ humanDate(lsfuel.created_at) }}</td>
                                  <td v-if="lsfuel.status == 'Pending Approval'">
                                      <button v-if="$root.can('ls-approve-fuel')" type="button" class="btn btn-sm btn-success" @click="approveFuel(lsfuel.id)">Approve</button>
                                  </td>
                                  <td v-else></td>
                                  <td>{{ lsfuel.approved_by ? lsfuel.approved_by.first_name + ' ' + lsfuel.approved_by.last_name : '' }}</td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>Fuel Id</th>
                                  <th>Status</th>
                                  <th>Vehicle</th>
                                  <th>Driver</th>
                                  <th>Fuel</th>
                                  <th>Created By</th>
                                  <th>Date</th>
                                  <th>Approval</th>
                                  <th>Approved By</th>
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
                lsfuels: [],
            };
        },

        created() {
          this.$root.isLoading = true;
          http.get('/api/lsfuelindex').then( response => {
            this.lsfuels = response.lsfuels;
            prepareTable();
            this.$root.isLoading = false;
          });
        },

        methods: {
          approveFuel(id) {
            this.$router.push('/ls/fuel/' + id);
          },

          humanDate(date) {
            return moment(date).format('ll');
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

            viewTrucksAwaitingFueling() {
              this.$router.push('/ls/fuel');
            }
        }
    }
</script>
