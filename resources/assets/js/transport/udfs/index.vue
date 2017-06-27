<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-2">
                                <h4>User Defined Fields</h4>
                            </div>
                            <div class="col-sm-7">
                                <transition
                                        name="custom-classes-transition"
                                        enter-active-class="animated flipInX"
                                        leave-active-class="animated flipOutX"
                                >
                                    <div v-if="showImport">
                                        <form id="importForm" action="#" @submit.prevent="importUdfs" class="form-inline pull-right" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="_token" v-model="csrf">
                                            <div class="form-group">
                                                <input type="file" class="form-control input-sm" id="import_file" name="import_file" required>
                                            </div>

                                            <button class="btn btn-success btn-xs" type="submit">Import</button>
                                        </form>

                                        <div class="clearfix"></div>
                                        <h6 class="pull-right"><a target="_blank" href="/templates/user_defined_fields.xls">Download Sample</a></h6>
                                    </div>
                                </transition>
                            </div>
                            <div class="col-sm-3">
                                <a class="btn btn-info btn-xs pull-right" @click="showImport = !showImport"><i class="fa fa-inbox"></i> Import</a>
                                <router-link to="/udfs/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Input Type</th>
                                <th>Status</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="udf in udfs">
                                <td>{{ udf.name }}</td>
                                <td>{{ udf.input_type }}</td>
                                <td v-if="udf.status">Active</td>
                                <td v-else>In-Active</td>
                                <td>{{ udf.module}}</td>
                                <td>{{ udf.description }}</td>
                                <td class="text-center">
                                    <span @click="edit(udf)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <button data-toggle="popover" :data-item="udf.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Input Type</th>
                                <th>Status</th>
                                <th>Module</th>
                                <th>Description</th>
                                <th>Actions</th>
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
            http.get('/api/udf').then(response => {
                this.udfs = response;
                this.setupConfirm();
                prepareTable();
            });

        },

        data() {
            return {
                udfs: [],
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
            importUdfs() {
                this.$root.isLoading = true;
                http.uploadFile('#import_file', '/api/udf/import')
                    .then((response) => {
                        this.$root.isLoading = false;
                        if (response.status != 'success') {
                            alert2(this.$root, [response.message], 'danger');
                            return;
                        }
                        $('table').dataTable().fnDestroy();
                        this.udfs = response.udfs;
                        prepareTable();
                        alert2(this.$root, [response.message], 'success');
                    }).catch((error) => {
                        this.$root.isLoading = false;
                        alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                    });
            },

            edit(udf) {
                window._router.push({path: '/udfs/' + udf.id + '/edit'})
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

                http.destroy('/api/udf/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;

                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.udfs = response.udfs;
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
