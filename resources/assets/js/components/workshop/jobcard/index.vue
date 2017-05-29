<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <router-link to="/job-card/create" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> New Job Card</router-link>
            </div>
            <br>
            <br>

            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading text-center">
                                <strong>Job Cards Not Approved</strong>
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
                                        <tr v-for="(card, index) in pending">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="edit(card)">JC-{{ card.id }}</a></td>
                                            <td>{{ card.vehicle_number }}</td>
                                            <td>{{ card.type.name }}</td>
                                            <td>{{ card.job_description }}</td>
                                            <td>{{ formatDate(card.created_at) }}</td>
                                            <td>{{ formatDate(card.expected_completion) }}</td>
                                            <td class="text-center">
                                                <span @click="edit(card)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                                <button data-toggle="popover" :data-item="card.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
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

                    <div class="col-sm-6">
                        <div class="panel panel-info">
                            <div class="panel-heading text-center">
                                <strong>Open Job Cards</strong>
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
                                        <tr v-for="(card, index) in open">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="edit(card)">JC-{{ card.id }}</a></td>
                                            <td>{{ card.vehicle_number }}</td>
                                            <td>{{ card.type.name }}</td>
                                            <td>{{ card.job_description }}</td>
                                            <td>{{ formatDate(card.created_at) }}</td>
                                            <td>{{ formatDate(card.expected_completion) }}</td>
                                            <td class="text-center">
                                                <span @click="edit(card)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                                <button data-toggle="popover" :data-item="card.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
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

        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/job-card').then(response => {
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

        computed: {
            pending() {
                return this.cards.filter(card => card.status == "Pending Approval");
            },
            open() {
                return this.cards.filter(card => card.status == "Approved");
            },
        },

        methods: {
            formatDate(date) {
                date = new Date(date);
                let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                let month = months[date.getMonth()];
                let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

                return day + ' ' + month + ' ' + date.getFullYear();
            },

            edit(record) {
                window._router.push({path: '/job-card/' + record.id + '/edit'})
            },




            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/truck/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.trucks = response.trucks;
                    prepareTable();
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
