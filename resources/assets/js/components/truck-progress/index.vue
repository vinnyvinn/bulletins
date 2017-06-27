<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks</strong>
                    </div>
                    <div class="panel-body">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Current Stage</th>
                                <th></th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="truck in trucks">
                                <td>{{ truck.plate_number }}</td>
                                <td>{{ truck.driver ? truck.driver.first_name + ' ' + truck.driver.last_name : 'No Driver' }}</td>
                                <td>{{ truck.location }}</td>
                                <td class="text-center">
                                    <span @click="progress(truck)" class="btn btn-xs btn-info"><i class="fa fa-check"></i> Next Step</span>
                                </td>
                            </tr>
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Plate Number</th>
                                <th>Driver</th>
                                <th>Current Stage</th>
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
            let state = this.$route.params.id ? '/' + this.$route.params.id : '';

            http.get('/api/progress' + state).then(response => {
                this.trucks = response.trucks;
                prepareTable();
            });
        },
        data() {
            return {
                trucks: [],
            };
        },

        methods: {
            progresssss(truck) {
                http.post('/api/progress/' + truck.id + '/edit', {}).then(response => {
                    alert2(this.$root, [response.message], 'success');
                    this.trucks = response.trucks;
                });
            },
            progress(truck) {
                window._router.push({path: '/progress/' + this.$route.params.id + '/' + truck.id});
//                let routes = [
//                    'pre-loading', 'loading', 'enroute', 'offloading', 'in-yard'
//                ];
//
//                if (routes.indexOf(this.$route.params.id) < 0) {
//
//                }
//                http.post('/api/progress/' + this.$route.params.id + '/edit', {}).then(response => {
//                    alert2(this.$root, [response.message], 'success');
//                    this.trucks = response.trucks;
//                });
            },
        }
    }
</script>
