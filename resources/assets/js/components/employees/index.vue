<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-2">
                                <h4>Employees</h4>
                            </div>
                            <!-- <div class="col-sm-7">
                                <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated flipInX"
                                        leave-active-class="animated flipOutX"
                                >
                                    <div v-if="showImport">
                                        <form id="importForm" action="#" @submit.prevent="importDrivers" class="form-inline pull-right" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" v-model="csrf">
                                            <div class="form-group">
                                                <input type="file" class="form-control input-sm" id="import_file" name="import_file" required>
                                            </div>

                                            <button class="btn btn-success btn-xs" type="submit">Import</button>
                                        </form>

                                        <div class="clearfix"></div>
                                        <h6 class="pull-right"><a target="_blank" href="/templates/drivers.xls">Download Sample</a></h6>
                                    </div>
                                </transition>
                            </div> -->
                            <div class="col-sm-10">
                               <!--  <a class="btn btn-info btn-xs pull-right" @click="showImport = !showImport"><i class="fa fa-inbox"></i> Import</a> -->
                                <router-link to="/employees/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Identification</th>
                                <th>Identification Number</th>
                                <th>Mobile Number</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="employee in employees">
                                <td>{{ employee.first_name }} {{ employee.last_name }}</td>
                                <td>{{ employee.identification_type }}</td>
                                <td>{{ employee.identification_number }}</td>
                                <td>{{ employee.mobile_phone }}</td>
                                <td>{{ employee.category }}</td>
                                <td class="text-center">
                                    <!-- <span @click="view(employee)" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></span> -->
                                    <span @click="edit(employee)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="employee.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Identification</th>
                                <th>Identification Number</th>
                                <th>Mobile Number</th>
                                <th>Category</th>
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
        data() {
            return {
                employees: [],
                showImport: false,
                csrf: window.Laravel.csrfToken
            };
        },
        created() {
          this.$root.isLoading = true;
            http.get('/api/employee').then(response => {
                this.employees = response.employees;
                prepareTable();
                this.setupConfirm();
                this.$root.isLoading = false;
            });

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
                        this.employees = response.employees;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    })
                    .catch((error) => {
                        this.$root.isLoading = false;
                        alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                    });
            },

            edit(employee) {
                window._router.push({path: '/employee/' + employee.id + '/edit'})
            },

            view(employee) {
                window._router.push({path: '/employee/' + employee.id})
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

                http.destroy('api/employee/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.employees = response.employees;
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
