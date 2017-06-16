<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Contracts</strong>
                        <router-link to="/contracts/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap">
                                <thead>
                                <tr>
                                    <th>Contract #</th>
                                    <th>Status</th>
                                    <th>Client</th>
                                    <th>Date Created</th>
                                    <th>Start Date</th>
                                    <th>Expected End Date</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="contract in contracts">
                                    <td>
                                        <router-link :to="'/contracts/' + contract.id">CNTR{{ contract.id }}</router-link>
                                    </td>
                                    <td>
                                      <span class="label label-default" v-if="contract.status == 'Pending Approval'">Pending Approval</span>
                                      <span class="label label-success" v-if="contract.status == 'Approved'">Approved</span>
                                    </td>
                                    <td>{{ contract.client.Name }}</td>
                                    <td>{{ date2(contract.created_at) }}</td>
                                    <td>{{ date2(contract.start_date) }}</td>
                                    <td>{{ date2(contract.end_date) }}</td>
                                    <td>{{ Number(contract.quantity).toLocaleString() }} Tonnes</td>
                                    <td>{{ $root.currency }} {{ Number(contract.amount).toLocaleString() }} {{ contract.rate }}</td>
                                    <td class="text-center" v-if="contract.status == 'Pending Approval'">
                                        <span @click="edit(contract)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button data-toggle="popover" :data-item="contract.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                    <td v-else></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Contract #</th>
                                    <th>Status</th>
                                    <th>Client</th>
                                    <th>Date Created</th>
                                    <th>Start Date</th>
                                    <th>Expected End Date</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
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
            http.get('/api/contract').then(response => {
                this.contracts = response.contracts;
                this.setupConfirm();
                prepareTable();
            });
        },

        data() {
            return {
                contracts: []
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
                window._router.push({path: '/contracts/' + contract.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/contract/' + id).then(response => {
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
