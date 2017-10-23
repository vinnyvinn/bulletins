<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Closed Incidents</strong>
                        <router-link to="/wsh/breakdown/open" class="btn btn-danger btn-xs pull-right">
                            <i class="fa fa-plus"></i> Open
                        </router-link>

                        <router-link to="/wsh/breakdown" class="btn btn-primary btn-xs pull-right">
                            <i class="fa fa-plus"></i> Unapproved Incidents
                        </router-link>

                        <router-link to="/wsh/breakdown/create" class="btn btn-success btn-xs pull-right">
                            <i class="fa fa-plus"></i> New Incident
                        </router-link>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable nowrap">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Incedent #</th>
                                    <th>Vehicle</th>
                                    <th>Driver</th>
                                    <th>Area</th>
                                    <th>Location</th>
                                    <th>Logged On</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(breakdown, index) in breakdowns">
                                    <td>{{ index + 1 }}</td>
                                    <td>
                                      <router-link :to="'/wsh/breakdown/' + breakdown.id">BRK-{{ breakdown.id }}</router-link>
                                    </td>
                                    <td>{{ breakdown.plate_number }}</td>
                                    <td>{{ breakdown.first_name }} {{ breakdown.last_name }}</td>
                                    <td>{{ breakdown.name }}</td>
                                    <td>{{ breakdown.location }}</td>
                                    <td>{{ formatDate(breakdown.created_at) }}</td>
                                    <td class="text-center">
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>No.</th>
                                  <th>Incedent #</th>
                                  <th>Vehicle</th>
                                  <th>Driver</th>
                                  <th>Area</th>
                                  <th>Location</th>
                                  <th>Logged On</th>
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
            http.get('/api/breakdown?status=Closed').then(response => {
                this.breakdowns = response.breakdowns;
                this.requisitions = response.requisitions;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                breakdowns: [],
            };
        },

        methods: {
            formatDate(date) {
                date = new Date(date);
                let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                let month = months[date.getMonth()];
                let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

                return day + ' ' + month + ' ' + date.getFullYear();
            },

            destroy(id) {
                //this.$root.isLoading = true;

                // http.destroy('api/truck/' + id).then(response => {
                //     if (response.status != 'success') {
                //         this.$root.isLoading = false;
                //         alert2(this.$root, [response.message], 'danger');
                //         return;
                //     }
                //     $('table').dataTable().fnDestroy();
                //     this.trucks = response.trucks;
                //     prepareTable();
                //     this.$root.isLoading = false;
                //     alert2(this.$root, [response.message], 'success');
                // }).catch((error) => {
                //     this.$root.isLoading = false;
                //     alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                // });
            }
        }
    }
</script>
