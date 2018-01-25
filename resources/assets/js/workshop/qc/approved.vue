<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Approved Quality Checks</strong>

                        <router-link to="/wsh/qc" class="btn btn-primary btn-xs pull-right">
                            Pending QCs
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
                                    <th>QC #</th>
                                    <th>Job Card #</th>
                                    <th>Vehicle</th>
                                    <th>Created On</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr v-for="(card, index) in cards">
                                    <td>{{ index + 1 }}</td>
                                    <td><router-link :to="'/wsh/qc/' + card.id + '/view'">QC-{{ card.id }}</router-link></td>
                                    <td><router-link :to="'/wsh/job-card/' + card.job_card_id">JC-{{ card.job_card_id }}</router-link></td>
                                    <td>{{ card.job_card.vehicle_number }}</td>
                                    <td>{{ formatDate(card.created_at) }}</td>
                                    <td></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>No.</th>
                                  <th>QC #</th>
                                  <th>Job Card #</th>
                                  <th>Vehicle</th>
                                  <th>Created On</th>
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
            http.get('/api/qc?status=Approved&station='+window.Laravel.station_id).then(response => {
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
