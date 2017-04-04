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
                                <td>{{ truck.driver ? truck.driver.name : 'No Driver' }}</td>
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
            http.get('/api/progress/offloading').then(response => {
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
            progress(truck) {
                http.post('/api/offloading/' + truck.id, {}).then(response => {
                    alert2(this.$root, [response.message], 'success');
                    this.trucks = response.trucks;
                });
//                window._router.push({path: '/progress/offloading/' + truck.id});
            },
        }
    }
</script>
