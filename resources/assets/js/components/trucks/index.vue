<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks</strong>
                        <router-link to="/trucks/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Max Weight</th>
                                <th>Status</th>
                                <th>Current State</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="truck in sharedState.state.trucks">
                                <td>{{ truck.plate_number }}</td>
                                <td>{{ truck.driver.name }}</td>
                                <td class="text-right">{{ Number(truck.load_weight).toLocaleString() }} Tonnes</td>
                                <td>{{ truck.status }}</td>
                                <td>{{ truck.currentState }}</td>
                                <td class="text-center">
                                    <span @click="edit(truck)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(truck)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Max Weight</th>
                                <th>Status</th>
                                <th>Current State</th>
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
            // setup the routes
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

            edit(truck) {
                let routeIndex = this.sharedState.state.trucks.indexOf(truck);
                window._router.push({path: '/trucks/' + routeIndex + '/edit'})
            },

            destroy(truck) {
                this.sharedState.state.trucks.splice(this.sharedState.state.trucks.indexOf(truck), 1);
            }
        }
    }
</script>
