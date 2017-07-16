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
                                    <td><button type="button" @click="allocate(truck)" name="button" class="btn btn-sm btn-success">Add</button></td>
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
                        <button type="button" name="button" class="btn btn-success btn-sm pull-right" @click="store">Save Allocation</button>
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
                                <tr v-for="allocatedtruck in allocation.allocatedtrucks">
                                    <td>{{ allocatedtruck.plate_number }}</td>
                                    <td v-if="allocatedtruck.trailer">{{ allocatedtruck.trailer.trailer_number}}</td>
                                    <td v-if="!allocatedtruck.trailer"> - </td>
                                    <td v-if="allocatedtruck.driver">{{ allocatedtruck.driver.first_name }}</td>
                                    <td><button type="button" @click="remove(allocatedtruck)" name="button" class="btn btn-sm btn-danger">Remove</button></td>
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
            this.allocation.contract_id = this.$route.params.id;
            this.trucks = response.trucks;
          });
        },

        data() {
            return {
                trucks: [],
                allocation: {
                  allocatedtrucks: [],
                  contract_id: ''
                }
            };
        },

        methods: {
            allocate (truck) {
              for(var i=0; i < this.trucks.length; i++) {
                 if(this.trucks[i].id == truck.id)
                 {
                    this.trucks.splice(i,1);
                 }
              }
              this.allocation.allocatedtrucks.push(truck);
            },

            remove (allocatedtruck) {
              for(var i=0; i < this.allocation.allocatedtrucks.length; i++) {
                 if(this.allocation.allocatedtrucks[i].id == allocatedtruck.id)
                 {
                    this.allocation.allocatedtrucks.splice(i,1);
                 }
              }
              this.trucks.push(allocatedtruck);
            },

            store () {
              http.post('/api/allocate_truck', this.allocation).then( response => {
                alert2(this.$root, [response.message], 'success');
                this.$router.push('/ls/trucks-allocation/' + this.allocation.contract_id);
              });

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
