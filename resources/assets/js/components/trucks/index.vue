<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-2">
                                <h4>Trucks</h4>
                            </div>
                            <div class="col-sm-7">
                                <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated flipInX"
                                        leave-active-class="animated flipOutX"
                                >
                                    <div v-if="showImport">
                                        <form id="importForm" action="#" @submit.prevent="importTrucks" class="form-inline pull-right" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" v-model="$root.csrf">
                                            <div class="form-group">
                                                <input type="file" class="form-control input-sm" id="import_file" name="import_file" required>
                                            </div>

                                            <button class="btn btn-success btn-xs" type="submit">Import</button>
                                        </form>

                                        <div class="clearfix"></div>
                                        <h6 class="pull-right"><a target="_blank" href="/templates/trucks.xls">Download Sample</a></h6>
                                    </div>
                                </transition>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info btn-xs pull-right" @click="showImport = !showImport"><i class="fa fa-inbox"></i> Import</a>
                                <router-link to="/trucks/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>T/Weight</th>
                                <th>Driver</th>
                                <th>Trailer</th>
                                <th>Status</th>
                                <th>Current Stage</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="truck in trucks">
                                <td>{{ truck.plate_number }}</td>
                                <td>{{ truck.make }}</td>
                                <td>{{ truck.model }}</td>
                                <td class="text-right">{{ Number(truck.max_load).toLocaleString() }} KGs</td>
                                <td>{{ truck.driver ? truck.driver.first_name + ' ' + truck.driver.last_name : 'No Driver' }}</td>
                                <td>{{ truck.trailer ? truck.trailer.trailer_number : 'No Trailer' }}</td>
                                <td>{{ truck.status }}</td>
                                <td>{{ truck.location }}</td>
                                <td class="text-center">
                                    <span @click="edit(truck)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="truck.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Plate Number</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>T/Weight</th>
                                <th>Driver</th>
                                <th>Trailer</th>
                                <th>Status</th>
                                <th>Current Stage</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/truck').then(response => {
                this.trucks = response.trucks;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                trucks: [],
                showImport: false
            };
        },

        methods: {
            importTrucks() {
                this.$root.isLoading = true;
                http.uploadFile('#import_file', '/api/truck/import')
                    .then((response) => {
                        this.$root.isLoading = false;
                        if (response.status != 'success') {
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }
                        $('table').dataTable().fnDestroy();
                        this.trucks = response.trucks;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            edit(truck) {
                window._router.push({path: '/trucks/' + truck.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/truck/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.trucks = response.trucks;
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
