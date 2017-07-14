<template>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks Available</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="truck in trucks">
                                    <td>{{ truck.plate_number }}</td>
                                    <td v-if="truck.trailer">{{ truck.trailer.trailer_number }}</td>
                                    <td v-if="!truck.trailer"> - </td>
                                    <td v-if="truck.driver">{{ truck.driver.first_name }}</td>
                                    <td><button type="button" @click="allocate(truck)" name="button" class="btn btn-sm btn-success">Allocate</button></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Plate #</th>
                                  <th>Trailer</th>
                                  <th>Driver</th>
                                  <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Allocated Trucks</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="allocatedtruck in allocatedtrucks">
                                    <td>{{ allocatedtruck.plate_number }}</td>
                                    <td v-if="allocatedtruck.trailer">{{ allocatedtruck.trailer.trailer_number}}</td>
                                    <td v-if="!allocatedtruck.trailer"> - </td>
                                    <td v-if="allocatedtruck.driver">{{ allocatedtruck.driver.first_name }}</td>
                                    <td><button type="button" @click="remove(allocatedtruck)" name="button" class="btn btn-sm btn-success">Remove</button></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Plate #</th>
                                  <th>Trailer</th>
                                  <th>Driver</th>
                                  <th></th>
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
        created() {
          http.get('/api/journey/create').then( response => {
            this.trucks = response.trucks;
          });
        },

        data() {
            return {
                trucks: [],
                allocatedtrucks: []
            };
        },

        methods: {
            allocate (truck) {
              this.trucks.splice(truck, function(truck) {
                
              });
              this.allocatedtrucks.push(truck);
            },

            remove (truck) {
              this.allocatedtrucks.splice(truck);
              this.trucks.push(truck);
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



            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/journey/' + id + '/?s=' + window.Laravel.station_id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    prepareTable();
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
