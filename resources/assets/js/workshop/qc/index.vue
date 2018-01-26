<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Pending Quality Checks</strong>

                        <router-link to="/wsh/qc/approved" class="btn btn-primary btn-xs pull-right">
                            Approved
                        </router-link>

                        <router-link to="/wsh/qc/disapproved" class="btn btn-danger btn-xs pull-right">
                            Disapproved
                        </router-link>

                        <router-link to="/wsh/qc/waivered" class="btn btn-success btn-xs pull-right">
                            Waivered
                        </router-link>

                        <router-link to="/wsh/qc/open" class="btn btn-warning btn-xs pull-right">
                            Pending Review
                        </router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable nowrap">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Card #</th>
                                    <th>Type</th>
                                    <th>Vehicle</th>
                                    <th>Job Type</th>
                                    <th>Description</th>
                                    <th>Created On</th>
                                    <th>Expected Completion</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(card, index) in cards">
                                    <td>{{ index + 1 }}</td>
                                    <td><router-link :to="'/wsh/job-card/' + card.id">JC-{{ card.id }}</router-link></td>
                                    <td>
                                      <router-link v-if="card.breakdown_id" :to="'/wsh/breakdown/' + card.breakdown_id">
                                        <span class="label label-danger">BREAKDOWN</span>
                                      </router-link>
                                      <span v-else class="label label-success">STANDARD</span>
                                    </td>
                                    <td>{{ card.vehicle_number }}</td>
                                    <td>{{ card.type.name }}</td>
                                    <td>{{ card.job_description }}</td>
                                    <td>{{ formatDate(card.created_at) }}</td>
                                    <td>{{ formatDate(card.expected_completion) }}</td>
                                    <td><a @click.prevent="viewCard(card.id)" class="btn btn-success btn-xs">PROCESS</a></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Card #</th>
                                    <th>Type</th>
                                    <th>Vehicle</th>
                                    <th>Job Type</th>
                                    <th>Description</th>
                                    <th>Created On</th>
                                    <th>Expected Completion</th>
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
            http.get('/api/qc?station='+window.Laravel.station_id).then(response => {
                this.cards = response.cards;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                cards: [],
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

            editCard(record) {
                window._router.push({path: '/wsh/qc/' + record + '/edit'})
            },

            viewCard(record) {
                window._router.push({ path: '/wsh/qc/' + record })
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
