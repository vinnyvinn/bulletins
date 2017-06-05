<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <router-link to="/job-card/create" class="btn btn-primary"><i class="fa fa-plus"></i> New Job Card</router-link>
                <router-link to="/parts/create" class="btn btn-primary"><i class="fa fa-plus"></i> Request Parts</router-link>
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
                                        <tr v-for="(card, index) in pendingCards">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewCard(card.id)">JC-{{ card.id }}</a></td>
                                            <td>{{ card.vehicle_number }}</td>
                                            <td>{{ card.type.name }}</td>
                                            <td>{{ card.job_description }}</td>
                                            <td>{{ formatDate(card.created_at) }}</td>
                                            <td>{{ formatDate(card.expected_completion) }}</td>
                                            <td class="text-center">
                                                <span @click="editCard(card.id)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
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

                        <div class="panel panel-warning">
                            <div class="panel-heading text-center">
                                <strong>Parts Requisitions Not Approved</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table datatable nowrap">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(item, index) in pendingRequisitions">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewRequisition(item.id)">PR-{{ item.id }}</a></td>
                                            <td><a @click.prevent="viewCard(item.job_card_id)">JC-{{ item.job_card_id }}</a></td>
                                            <td>{{ item.job_card.vehicle_number }}</td>
                                            <td>{{ formatDate(item.created_at) }}</td>
                                            <td class="text-center">
                                                <span @click="editRequisition(item.id)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                                <button data-toggle="popover" :data-item="item.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading text-center">
                                <strong>Issued Parts Requisitions</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table datatable nowrap">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(item, index) in issuedRequisitions">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewRequisition(item.id)">PR-{{ item.id }}</a></td>
                                            <td><a @click.prevent="viewCard(item.job_card_id)">JC-{{ item.job_card_id }}</a></td>
                                            <td>{{ item.job_card.vehicle_number }}</td>
                                            <td>{{ formatDate(item.created_at) }}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
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
                                        <tr v-for="(card, index) in openCards">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewCard(card.id)">JC-{{ card.id }}</a></td>
                                            <td>{{ card.vehicle_number }}</td>
                                            <td>{{ card.type.name }}</td>
                                            <td>{{ card.job_description }}</td>
                                            <td>{{ formatDate(card.created_at) }}</td>
                                            <td>{{ formatDate(card.expected_completion) }}</td>
                                            <td class="text-center">
                                                <span @click="editCard(card.id)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
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

                        <div class="panel panel-info">
                            <div class="panel-heading text-center">
                                <strong>Parts Requisitions Approved, Not Issued</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table datatable nowrap">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(item, index) in approvedRequisitions">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewRequisition(item.id)">PR-{{ item.id }}</a></td>
                                            <td><a @click.prevent="viewCard(item.job_card_id)">JC-{{ item.job_card_id }}</a></td>
                                            <td>{{ item.job_card.vehicle_number }}</td>
                                            <td>{{ formatDate(item.created_at) }}</td>
                                            <td class="text-center">
                                            </td>
                                        </tr>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-success">
                            <div class="panel-heading text-center">
                                <strong>Closed Parts Requisitions</strong>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table datatable nowrap">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr v-for="(item, index) in closedRequisitions">
                                            <td>{{ index + 1 }}</td>
                                            <td><a @click.prevent="viewRequisition(item.id)">PR-{{ item.id }}</a></td>
                                            <td><a @click.prevent="viewCard(item.job_card_id)">JC-{{ item.job_card_id }}</a></td>
                                            <td>{{ item.job_card.vehicle_number }}</td>
                                            <td>{{ formatDate(item.created_at) }}</td>
                                            <td class="text-center"></td>
                                        </tr>
                                        </tbody>

                                        <tfoot>
                                        <tr>
                                            <th>No.</th>
                                            <th>Requisition #</th>
                                            <th>Card #</th>
                                            <th>Vehicle</th>
                                            <th>Requested On</th>
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

        computed: {
            pendingCards() {
                return this.cards.filter(card => card.status == "Pending Approval");
            },
            openCards() {
                return this.cards.filter(card => card.status == "Approved");
            },
            pendingRequisitions() {
                return this.requisitions.filter(item => item.status == "Pending Approval");
            },
            approvedRequisitions() {
                return this.requisitions.filter(item => item.status == "Approved");
            },
            issuedRequisitions() {
                return this.requisitions.filter(item => item.status == "Issued");
            },
            closedRequisitions() {
                return this.requisitions.filter(item => item.status == "Closed");
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

            editCard(record) {
                window._router.push({path: '/job-card/' + record + '/edit'})
            },

            viewCard(record) {
                window._router.push({ path: '/job-card/' + record })
            },

            editRequisition(record) {
                window._router.push({path: '/parts/' + record + '/edit'})
            },

            viewRequisition(record) {
                window._router.push({ path: '/parts/' + record })
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
