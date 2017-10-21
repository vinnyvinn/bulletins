<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-4">
                                <strong>Contracts</strong>
                            </div>

                            <div class="col-sm-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <select class="form-control input-sm" name="month" id="month" v-model="period">
                                            <option :value="null" disabled>Select Period</option>
                                            <option v-for="index in 12" :value="index">{{ index }} Month{{ index > 1 ? 's' : '' }}</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="datepicker form-control input-sm" v-model="endMonth" placeholder="To Date">
                                            <span class="input-group-btn">
                                                <button @click.prevent="filterRows" class="btn btn-success btn-sm">Filter</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <router-link v-if="$root.can('create-contract')" to="/contracts/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> New Contract</router-link>
                                <router-link v-if="$root.can('create-contract-template')" to="/contract-templates" class="btn btn-warning btn-xs pull-right"><i class="fa fa-eye"></i> Templates</router-link>
                                <router-link to="/config_fields/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Contract Settings</router-link>

                            </div>
                        </div>
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
                                    <th class="text-right">Total Delivered</th>
                                    <th class="text-right">Remaining Qty</th>
                                    <th class="text-right">Journeys Made</th>
                                    <th>Rate</th>
                                    <th class="noprint"></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="contract in contracts">
                                    <td>
                                        <router-link v-if="$root.can('view-contract') || $root.can('approve-contract')" :to="'/contracts/' + contract.id">CNTR{{ contract.id }}</router-link>
                                        <span v-else>CNTR{{ contract.id }}</span>
                                    </td>
                                    <td>
                                      <span class="label label-info" v-if="contract.status == 'Pending Approval'">Pending Approval</span>
                                      <span class="label label-success" v-if="contract.status == 'Approved'">Approved</span>
                                      <span class="label label-default" v-if="contract.status == 'Closed'">Closed</span>

                                    </td>
                                    <td>{{ contract.client.Name }}</td>
                                    <td>{{ date2(contract.created_at) }}</td>
                                    <td>{{ date2(contract.start_date) }}</td>
                                    <td>{{ date2(contract.end_date) }}</td>
                                    <td>{{ Number(contract.quantity).toLocaleString() }} KGs</td>
                                    <td class="text-right">{{ Number(contract.totalDeliveries).toLocaleString() }} KGs</td>
                                    <td class="text-right">{{ (Number(contract.quantity) - Number(contract.totalDeliveries)).toLocaleString() }} KGs</td>
                                    <td class="text-right">{{ Number(contract.journeys_count).toLocaleString() }}</td>
                                    <td>{{ $root.currency }} {{ Number(contract.amount).toLocaleString() }} {{ contract.rate }}</td>
                                    <td class="text-center">
                                        <span v-if="(contract.status != 'Closed') && ($root.can('edit-contract'))" @click="edit(contract)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button data-toggle="popover" v-if="$root.can('delete-contract')" :data-item="contract.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
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
                                    <th class="text-right">Total Delivered</th>
                                    <th class="text-right">Remaining Qty</th>
                                    <th class="text-right">Journeys Made</th>
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
          this.$root.isLoading = true;
            http.get('/api/contract').then(response => {
                this.contracts = response.contracts;
                this.setupConfirm();
                prepareTable();
                this.$root.isLoading = false;
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
            },

            filterRows() {
                if (! this.period) return;
                if (! this.endMonth) return;
                this.$root.isLoading = true;
                http.get('/api/contract?duration=' + this.period + '&date=' + this.endMonth).then(response => {
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
                    this.setupConfirm();
                    prepareTable();
                    this.$root.isLoading = false;
                });
            }
        }
    }
</script>
