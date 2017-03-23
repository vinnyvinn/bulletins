<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Contracts</strong>
                        <router-link to="/contracts/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Client</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Quantity</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="contract in sharedState.state.contracts">
                                <td>{{ contract.client.name }}</td>
                                <td>{{ contract.start_date }}</td>
                                <td>{{ contract.end_date }}</td>
                                <td>{{ Number(contract.quantity).toLocaleString() }} Tonnes</td>
                                <td class="text-center">
                                    <span @click="edit(contract)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(contract)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Client</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Quantity</th>
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
            // setup the contracts
        },
        mounted() {
            this.prepareTable();
        },
        data() {
            return {
                sharedState: window._mainState,
                privateState: {

                }
            };
        },

        methods: {
            prepareTable() {
                $('table').dataTable();
            },

            edit(contract) {
                let index = this.sharedState.state.contracts.indexOf(contract);
                window._router.push({path: '/contracts/' + index + '/edit'})
            },

            destroy(contract) {
                this.sharedState.state.contracts.splice(this.sharedState.state.contracts.indexOf(contract), 1);
            }
        }
    }
</script>
