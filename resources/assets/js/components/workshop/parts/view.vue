<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Requisition Order #JC-{{ requisition_number }}</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form">

                    <div class="row">
                        <div class="col-sm-4">
                            <h4><strong>Requisition Order Number:</strong></h4>
                            <h5>JC-{{ requisition_number }}</h5>
                        </div>
                        <div class="col-sm-4">
                            <h4><strong>Requested By:</strong></h4>
                            <h5>{{ user.first_name }} {{ user.last_name }}</h5>
                        </div>
                        <div class="col-sm-4">
                            <h4><strong>Requested On:</strong></h4>
                            <h5>{{ formatDate(requested_on) }}</h5>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="job_card_id">Job Card</label>
                                <input disabled :value="'JC-' + requisition.job_card_id" name="job_card_id" id="job_card_id" class="form-control input-sm">
                            </div>

                            <div class="form-group">
                                <label>Vehicle Number</label>
                                <h5>{{ requisition.vehicle_number }}</h5>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mechanic_findings">Mechanic's Findings</label>
                                <textarea disabled name="mechanic_findings" id="mechanic_findings" cols="20" rows="5" class="form-control input-sm">{{ requisition.mechanic_findings }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Requested</th>
                                    <th>Approved</th>
                                    <th>Issued</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in requisition.lines">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ item.item_name }}</td>
                                    <td>{{ item.requested_quantity }}</td>
                                    <td>
                                        <input v-if="status == 'Pending Approval' && can('approve-requisition')" type="number"
                                               v-model="item.approved_quantity" class="form-control input-sm" :max="item.requested_quantity" number>
                                        <span v-else>{{ item.approved_quantity }}</span>
                                    </td>
                                    <td>
                                        <input v-if="status == 'Approved' && can('issue-requisition')" type="number"
                                               v-model="item.issued_quantity" class="form-control input-sm" :max="item.issued_quantity" number>
                                        <span v-else>{{ item.issued_quantity }}</span>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="form-group">
                        <span v-if="(status == 'Pending Approval' && can('approve-requisition'))">
                            <button class="btn btn-success" @click.prevent="approve()">Approve Job Card</button>
                            <button class="btn btn-danger" @click.prevent="disapprove()">Disapprove Job Card</button>
                        </span>
                        <span v-if="(status == 'Approved' && can('issue-requisition'))">
                            <button class="btn btn-success" @click.prevent="approve()">Issue Parts</button>
                        </span>
                        <router-link to="/job-card" class="btn btn-danger">Back</router-link>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                status: '',
                requisition_number: '',
                user: {},
                requested_on: '',
                parts: [],
                cards: [],
                requisition: {
                    job_card_id: null,
                    mechanic_findings: '',
                    lines: [],
                    status: 'Pending Approval'
                }
            };
        },

        computed: {
            vehicle() {
                let selected =  this.vehicles.filter((item) => (item.id == this.card.vehicle_id));
                selected = selected.length ? selected[0]: { driver:{} };
                selected.driver = selected.driver ? selected.driver : { name: 'No Driver' };

                return selected;
            },

            jobTypes() {
                let selected = this.job_types.filter((t) => (t.service_type == this.card.service_type));

                return selected.length ? selected : [];
            },

            jobType() {
                let selected =  this.job_types.filter((item) => item.id == this.card.workshop_job_type_id);
                selected = selected.length ? selected[0]: { operations:[] };

                return selected;
            },

            operation() {
                let selected =  this.jobType.operations.filter((item) => item.id == this.task.operation_id);
                selected = selected.length ? selected[0]: { tasks:[] };
                selected.tasks = selected.tasks ? selected.tasks : [];

                return selected;
            },
        },

        created() {
            this.checkState();
        },

        methods: {
            can(permission) {
                return window.can(permission)
            },
            formatDate(date) {
                date = new Date(date);
                let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

                let month = months[date.getMonth()];
                let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

                return day + ' ' + month + ' ' + date.getFullYear();
            },


            checkState() {
                if (this.$route.params.id) {
                    this.$root.isLoading = true;

                    http.get('/api/parts/' + this.$route.params.id).then((response) => {
                        this.requisition = response.requisition.raw_data;
                        this.requisition_number = response.requisition.id;
                        this.user = response.requisition.user;
                        this.requested_on = response.requisition.created_at;
                        this.status = response.requisition.status;

                        this.requisition.lines.map(item => {
                            item.old_approved = item.approved_quantity;
                            item.old_issued = item.issued_quantity;
                            item.approved_quantity = 'Pending';
                            item.issued_quantity = 'Pending';
                            item.issued_quantity = this.status == 'Approved' ? item.approved_quantity : item.issued_quantity;

                            if (this.status == 'Pending Approval') {
                                item.approved_quantity = item.requested_quantity;

                                return item;
                            }

                            if (this.status == 'Approved') {
                                item.approved_quantity = item.old_approved;
                                item.issued_quantity = item.approved_quantity;

                                return item;
                            }

                            item.approved_quantity = item.old_approved;
                            item.issued_quantity = item.old_issued;

                            return item;
                        });

                        this.$root.isLoading = false;
                    });
                }
            },

            approve() {
                this.$root.isLoading = true;
                http.post('/api/parts/' + this.$route.params.id + '/approve', this.requisition).then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    this.$root.isLoading = false;
                    window._router.push({ path: '/job-card' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            disapprove() {
                this.$root.isLoading = true;
                http.post('/api/parts/' + this.$route.params.id + '/disapprove', this.requisition).then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    this.$root.isLoading = false;
                    window._router.push({ path: '/job-card' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
