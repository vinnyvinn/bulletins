<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Job Cards Not Approved</strong>

                        <router-link to="/wsh/job-card/closed" class="btn btn-danger btn-xs pull-right">
                            <i class="fa fa-plus"></i> Closed
                        </router-link>

                        <router-link to="/wsh/job-card" class="btn btn-primary btn-xs pull-right">
                            <i class="fa fa-plus"></i> Unapproved Job Cards
                        </router-link>

                        <router-link to="/wsh/job-card/create" class="btn btn-success btn-xs pull-right">
                            <i class="fa fa-plus"></i> New Job Card
                        </router-link>

                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table datatable nowrap">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Card #</th>
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
                                    <td><a @click.prevent="viewCard(card.id)">JC-{{ card.id }}</a></td>
                                    <td>{{ card.vehicle_number }}</td>
                                    <td>{{ card.type.name }}</td>
                                    <td>{{ card.job_description }}</td>
                                    <td>{{ formatDate(card.created_at) }}</td>
                                    <td>{{ formatDate(card.expected_completion) }}</td>
                                    <td class="text-center">
                                        <span @click="editCard(card.id)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <!-- <button data-toggle="popover" :data-item="card.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button> -->
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Card #</th>
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
            http.get('/api/job-card?status=Approved').then(response => {
                this.cards = response.cards;
                this.requisitions = response.requisitions;
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
                prepareTable();
            });
        },
        data() {
            return {
                cards: [],
                requisitions: [],
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
                window._router.push({path: '/wsh/job-card/' + record + '/edit'})
            },

            viewCard(record) {
                window._router.push({ path: '/wsh/job-card/' + record })
            },

            editRequisition(record) {
                window._router.push({path: '/wsh/parts/' + record + '/edit'})
            },

            viewRequisition(record) {
                window._router.push({ path: '/wsh/parts/' + record })
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
