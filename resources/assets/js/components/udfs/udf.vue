<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4>User Defined Fields</h4>
                            </div>
                        </div>
                        <router-link to="/udfs/create"  class="btn btn-info btn-sm pull-right"> Create UDF </router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Input Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <!--<tr v-for="driver in drivers">-->
                                <!--<td>{{ driver.name }}</td>-->
                                <!--<td>{{ driver.national_id }}</td>-->
                                <!--<td>{{ driver.dl_number }}</td>-->
                                <!--<td>{{ driver.mobile }}</td>-->
                                <!--<td class="text-center">-->
                                    <!--<span @click="edit(driver)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>-->
                                    <!--<button data-toggle="popover" :data-item="driver.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>-->
                                <!--</td>-->
                            <!--</tr>-->
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>National ID</th>
                                <th>DL Number</th>
                                <th>Mobile Number</th>
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
            http.get('/api/driver').then(response => {
                this.drivers = response;
                this.setupConfirm();
                prepareTable();
            });

        },

        data() {
            return {
                drivers: [],
                showImport: false,
                csrf: window.Laravel.csrfToken
            };
        },

        methods: {
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            importDrivers() {
                this.$root.isLoading = true;
                http.uploadFile('#import_file', '/api/driver/import')
                    .then((response) => {
                        this.$root.isLoading = false;
                        if (response.status != 'success') {
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }
                        $('table').dataTable().fnDestroy();
                        this.drivers = response.drivers;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            edit(driver) {
                window._router.push({path: '/drivers/' + driver.id + '/edit'})
            },

            flatten(arr) {
                return arr.reduce((acc, val) => acc.concat(
                    Array.isArray(val) ? flatten(val) : val
                    ),
                    []
                )
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
                    this.drivers = response.drivers;
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
