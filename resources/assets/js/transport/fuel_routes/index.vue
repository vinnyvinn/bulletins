<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-9">
                                <h4>Fuel Per Route</h4>
                            </div>

                            <div class="col-sm-3">
                                <router-link to="/fuel-routes/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th class="text-right">Distance</th>
                                <th class="text-right">Fuel Required</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="fuelRoute in fuelRoutes">
                                <td>{{ fuelRoute.route.source }}</td>
                                <td>{{ fuelRoute.route.destination }}</td>
                                <td>{{ fuelRoute.model.make.name }}</td>
                                <td>{{ fuelRoute.model.name }}</td>
                                <td class="text-right">{{ fuelRoute.route.distance }} KM</td>
                                <td class="text-right">
                                    <input v-if="fuelRoute.isEdit" @keyup.enter="updateRow(fuelRoute)" onClick="this.select()" class="form-control input-sm" type="number" min="1" v-model="fuelRoute.editValue">
                                    <span v-else>{{ Number(fuelRoute.amount).toLocaleString() }} Ltrs</span>
                                </td>
                                <td class="text-center">
                                    <span v-if="fuelRoute.isEdit" @click="updateRow(fuelRoute)" class="btn btn-xs btn-success"><i class="fa fa-check"></i></span>
                                    <span v-else @click="fuelRoute.isEdit = true" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="fuelRoute.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th class="text-right">Distance</th>
                                <th class="text-right">Fuel Required</th>
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
            http.get('/api/fuel-route').then(response => {
                this.fuelRoutes = response.fuelRoutes;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },

        data() {
            return {
                fuelRoutes: [],
                showImport: false
            };
        },

        methods: {
            updateRow(fuelRoute) {
                if (fuelRoute.amount == fuelRoute.editValue) {
                    fuelRoute.isEdit = false;
                    return;
                }

                this.$root.isLoading = true;

                http.put('/api/fuel-route/' + fuelRoute.id, fuelRoute).then(response => {
                    fuelRoute.amount = response.fuelRoute.amount;
                    fuelRoute.isEdit = false;
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch(() => this.$root.isLoading = false);
            },

            edit(route) {
                window._router.push({path: '/fuel-routes/' + route.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/fuel-route/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.fuelRoutes = response.fuelRoutes;
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
