<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Truck Allocations</strong>
                        <router-link to="/allocation/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Allocate</router-link>
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
                            <tr v-for="allocation in allocations">
                                <td>{{ allocation.contract.client.Name }} ({{ allocation.contract.client.Account }})</td>
                                <td>{{ allocation.contract.name }}</td>
                                <td>{{ allocation.plate_number }}</td>
                                <td>{{ date2(allocation.contract.start_date) }}</td>
                                <td>{{ date2(allocation.contract.end_date) }}</td>
                                <td class="text-center">
                                    <span v-if="allocation.location == 'Pre-Loading'" @click="edit(allocation)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                    <span v-if="allocation.location == 'Pre-Loading'" @click="destroy(allocation)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
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
            http.get('/api/allocation').then(response => {
                this.allocations = response.allocations;
                prepareTable();
            });
        },

        data() {
            return {
                allocations: []
            };
        },

        methods: {
            date2(value) {
                return window._date2(value);
            },

            edit(allocation) {
                window._router.push({path: '/allocation/' + allocation.id + '/edit'})
            },

            destroy(allocation) {
                http.destroy('/api/allocation/' + allocation.id, {}).then(response => {
                    alert2(this.$root, [response.message], 'success');
                    this.allocations = response.allocations;
                });
            }
        }
    }
</script>
