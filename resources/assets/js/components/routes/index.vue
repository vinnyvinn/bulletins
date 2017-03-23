<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Routes</strong>
                        <router-link to="/routes/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Distance</th>
                                <th>Billing</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="route in sharedState.state.routes">
                                <td>{{ route.source }}</td>
                                <td>{{ route.destination }}</td>
                                <td>{{ route.distance }}</td>
                                <td>{{ route.payment_type }}</td>
                                <td class="text-center">
                                    <span @click="edit(route)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span @click="destroy(route)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>From</th>
                                <th>To</th>
                                <th>Distance</th>
                                <th>Billing</th>
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

            edit(route) {
                let routeIndex = this.sharedState.state.routes.indexOf(route);
                window._router.push({path: '/routes/' + routeIndex + '/edit'})
            },

            destroy(route) {
                this.sharedState.state.routes.splice(this.sharedState.state.routes.indexOf(route), 1);
            }
        }
    }
</script>
