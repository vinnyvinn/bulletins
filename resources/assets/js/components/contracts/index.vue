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
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Contract #</th>
                                <th>Client</th>
                                <th>Date Created</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="contract in contracts">
                                <td>CNTR{{ contract.id }}</td>
                                <td>{{ contract.client.Name }}</td>
                                <td>{{ date2(contract.created_at) }}</td>
                                <td>{{ date2(contract.start_date) }}</td>
                                <td>{{ date2(contract.end_date) }}</td>
                                <td>{{ Number(contract.quantity).toLocaleString() }} Tonnes</td>
                                <td>{{ $root.currency }} {{ Number(contract.amount).toLocaleString() }} {{ contract.rate }}</td>
                                <td class="text-center">
                                    <span @click="edit(contract)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(contract)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Contract #</th>
                                <th>Client</th>
                                <th>Date Created</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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
</template>

<script>
    export default {
        created() {
            http.get('/api/contract').then(response => {
                this.contracts = response.contracts;
                prepareTable();
            });
        },

        data() {
            return {
                contracts: []
            };
        },

        methods: {
            date2(value) {
                return window._date2(value);
            },

            edit(contract) {
                window._router.push({path: '/contracts/' + contract.id + '/edit'})
            },

            destroy(contract) {
                this.sharedState.state.contracts.splice(this.sharedState.state.contracts.indexOf(contract), 1);
            }
        }
    }
</script>
