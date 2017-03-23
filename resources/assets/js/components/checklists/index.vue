<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Allocations</strong>
                        <router-link to="/allocation/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Client</th>
                                <th>Contract</th>
                                <th>Truck</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="allocation in sharedState.state.allocations">
                                <td>{{ allocation.contract.client.name }}</td>
                                <td>{{ allocation.contract.name }}</td>
                                <td>{{ allocation.truck.plate_number }}</td>
                                <td>{{ allocation.contract.start_date }}</td>
                                <td>{{ allocation.contract.end_date }}</td>
                                <td class="text-center">
                                    <span @click="edit(allocation)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(allocation)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Client</th>
                                <th>Contract</th>
                                <th>Truck</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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

            edit(allocation) {
                let index = this.sharedState.state.allocations.indexOf(allocation);
                window._router.push({path: '/allocation/' + index + '/edit'})
            },

            destroy(allocation) {
                this.sharedState.state.allocations.splice(this.sharedState.state.allocations.indexOf(allocation), 1);
            }
        }
    }
</script>
