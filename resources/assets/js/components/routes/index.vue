<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-2">
                                <h4>Routes</h4>
                            </div>
                            <div class="col-sm-7">
                                <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated flipInX"
                                        leave-active-class="animated flipOutX"
                                >
                                    <div v-if="showImport">
                                        <form id="importForm" action="#" @submit.prevent="importRoutes" class="form-inline pull-right" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" v-model="$root.csrf">
                                            <div class="form-group">
                                                <input type="file" class="form-control input-sm" id="import_file" name="import_file" required>
                                            </div>

                                            <button class="btn btn-success btn-xs" type="submit">Import</button>
                                        </form>

                                        <div class="clearfix"></div>
                                        <h6 class="pull-right"><a target="_blank" href="/templates/routes.xls">Download Sample</a></h6>
                                    </div>
                                </transition>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info btn-xs pull-right" @click="showImport = !showImport"><i class="fa fa-inbox"></i> Import</a>
                                <router-link to="/routes/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th class="text-right">Distance</th>
                                <th class="text-right">Fuel Required</th>
                                <th class="text-right">Allowance Amount</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="route in routes">
                                <td>{{ route.source }}</td>
                                <td>{{ route.destination }}</td>
                                <td class="text-right">{{ route.distance }} KM</td>
                                <td class="text-right">{{ Number(route.fuel_required).toLocaleString() }} Ltrs</td>
                                <td class="text-right">KES {{ Number(route.allowance_amount).toLocaleString() }}</td>
                                <td class="text-center">
                                    <span @click="view(route)" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></span>
                                    <span @click="edit(route)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="route.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Distance</th>
                                <th>Fuel Required</th>
                                <th>Allowance Amount</th>
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
            http.get('/api/route').then(response => {
                this.routes = response.routes;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },

        data() {
            return {
                routes: [],
                showImport: false
            };
        },

        methods: {
            importRoutes() {
                this.$root.isLoading = true;
                http.uploadFile('#import_file', '/api/route/import')
                    .then((response) => {
                        this.$root.isLoading = false;
                        if (response.status != 'success') {
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }
                        $('table').dataTable().fnDestroy();
                        this.routes = response.routes;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            edit(route) {
                window._router.push({path: '/routes/' + route.id + '/edit'})
            },
            view(route) {
                window._router.push({path: '/routes/' + route.id})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/driver/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.routes = response.routes;
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
