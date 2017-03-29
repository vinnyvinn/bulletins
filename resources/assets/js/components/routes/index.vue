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
                                <th class="text-right">Distance</th>
                                <th class="text-right">Fuel Required</th>
                                <th class="text-right">Allowance Amount</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="route in routes">
                                <td>{{ route.source }}</td>
                                <td>{{ route.destination }}</td>
                                <td class="text-right">{{ route.distance }} KM</td>
                                <td class="text-right">{{ Number(route.fuel_required).toLocaleString() }} Ltrs</td>
                                <td class="text-right">KES {{ Number(route.allowance_amount).toLocaleString() }}</td>
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
                                <th>Fuel Required</th>
                                <th>Allowance Amount</th>
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
            http.get('/api/route').then(response => {
                this.routes = response.routes;
                prepareTable();
            });
        },

        data() {
            return {
                routes: [],
            };
        },

        methods: {
            edit(route) {
                window._router.push({path: '/routes/' + route.id + '/edit'})
            },

            destroy(route) {
                this.sharedState.state.routes.splice(this.sharedState.state.routes.indexOf(route), 1);
            }
        }
    }
</script>
