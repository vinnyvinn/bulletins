<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Contract Templates</strong>
                                <router-link to="/contract-templates/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> New Template</router-link>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap">
                                <thead>
                                <tr>
                                    <th>Template #</th>
                                    <th>Name</th>
                                    <th>Date Created</th>
                                    <th class="noprint"></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="contract in contracts">
                                    <td>
                                        <router-link :to="'/contract-templates/' + contract.id">CNTR-TMPL-{{ contract.id }}</router-link>
                                    </td>
                                    <td>{{ contract.name }}</td>
                                    <td>{{ date2(contract.created_at) }}</td>
                                    <td class="text-center">
                                        <span @click="useTemplate(contract)" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> Use Template</span>
                                        <span @click="edit(contract)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button data-toggle="popover" :data-item="contract.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Template #</th>
                                    <th>Name</th>
                                    <th>Date Created</th>
                                    <th class="noprint"></th>
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
            http.get('/api/contract-template').then(response => {
                this.contracts = response.contracts;
                this.setupConfirm();
                prepareTable();
            });
            $(document).ready(() => {
                $('.datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    endDate: '0d',
                    clearBtn: true,
                    todayBtn: true,
                    todayHighlight: true,
                }).on('change', (e) => {
                    this.endMonth = e.target.value;
                });
            });
        },

        data() {
            return {
                contracts: [],
                endMonth: null,
                period: null,
            };
        },

        methods: {
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },

            edit(contract) {
                window._router.push({path: '/contract-templates/' + contract.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/contract-template/' + id).then(response => {
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
            },

            useTemplate(template) {
                window._router.push({path: '/contracts/create/' + template.id})
            }
        }
    }
</script>
