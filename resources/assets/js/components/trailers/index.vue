<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-2">
                                <h4>Trailers</h4>
                            </div>
                            <div class="col-sm-7">
                                <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated flipInX"
                                        leave-active-class="animated flipOutX"
                                >
                                    <div v-if="showImport">
                                        <form id="importForm" action="#" @submit.prevent="importTrailers" class="form-inline pull-right" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" v-model="$root.csrf">
                                            <div class="form-group">
                                                <input type="file" class="form-control input-sm" id="import_file" name="import_file" required>
                                            </div>

                                            <button class="btn btn-success btn-xs" type="submit">Import</button>
                                        </form>

                                        <div class="clearfix"></div>
                                        <h6 class="pull-right"><a target="_blank" href="/templates/trailers.xls">Download Sample</a></h6>
                                    </div>
                                </transition>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info btn-xs pull-right" @click="showImport = !showImport"><i class="fa fa-inbox"></i> Import</a>
                                <router-link to="/trailers/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Trailer Number</th>
                                <th>Make</th>
                                <th>Type</th>
                                <th>Attached To</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="trailer in trailers">
                                <td>{{ trailer.trailer_number }}</td>
                                <td>{{ trailer.make }}</td>
                                <td>{{ trailer.type }}</td>
                                <td>{{ trailer.truck ? trailer.truck.plate_number : 'No Truck' }}</td>
                                <td class="text-center">
                                    <span @click="view(trailer)" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></span>
                                    <span @click="edit(trailer)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="trailer.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Trailer Number</th>
                                <th>Make</th>
                                <th>Type</th>
                                <th>Attached To</th>
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
            http.get('/api/trailer').then(response => {
                this.trailers = response.trailers;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                trailers: [],
                showImport: false
            };
        },

        methods: {
            importTrailers() {
                this.$root.isLoading = true;
                http.uploadFile('#import_file', '/api/trailer/import')
                    .then((response) => {
                        this.$root.isLoading = false;
                        if (response.status != 'success') {
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }
                        $('table').dataTable().fnDestroy();
                        this.trailers = response.trailers;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            edit(trailer) {
                window._router.push({path: '/trailers/' + trailer.id + '/edit'})
            },

            view(trailer) {
                window._router.push({path: '/trailers/' + trailer.id})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/trailer/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.trailers = response.trailers;
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
