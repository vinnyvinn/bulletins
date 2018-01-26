<template>
    <div>
        <div v-html="printout"></div>
        <div class="container hidden-print">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Requisition Order #PR-{{ requisition_number }}</strong>
                </div>

                <div class="panel-body">
                    <form action="#" role="form" id="viewForm">

                        <div class="row">
                            <div class="col-sm-4">
                                <h4>
                                    <strong>Requisition Order Number:</strong>
                                </h4>
                                <h5>JC-{{ requisition_number }}</h5>
                            </div>
                            <div class="col-sm-4">
                                <h4>
                                    <strong>Requested By:</strong>
                                </h4>
                                <h5>{{ user.first_name }} {{ user.last_name }}</h5>
                            </div>
                            <div class="col-sm-4">
                                <h4>
                                    <strong>Requested On:</strong>
                                </h4>
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
                                            <th v-if="status == 'Approved'">Previous Issue</th>
                                            <th>{{ status == 'Approved' ? 'To Issue' : 'Issued' }}</th>
                                            <th>Previous Consumption</th>
                                            <th>Consumed</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in requisition.lines">
                                            <td>{{ index + 1}}</td>
                                            <td>{{ item.item_name }}</td>
                                            <td class="text-right">{{ item.requested_quantity }}</td>
                                            <td class="text-right">
                                                <input v-if="status == 'Pending Approval' && can('approve-requisition')" type="number" v-model="item.approved_quantity" class="form-control input-sm" min="0" number>
                                                <span v-else>{{ item.approved_quantity }}</span>
                                            </td>
                                            <td v-if="status == 'Approved'" class="text-right">
                                                {{ item.old_issued }}
                                            </td>
                                            <td class="text-right">
                                                <input v-if="status == 'Approved' && can('issue-requisition') && (item.actual_old_issued < item.approved_quantity)" type="number" v-model="item.issued_quantity" class="form-control input-sm" min="0" :max="item.approved_quantity - item.actual_old_issued" number>
                                                <span class="text-right" v-else>{{ item.issued_quantity }}</span>
                                            </td>
                                            <td class="text-right">{{ isNaN(parseInt(item.old_consumed_quantity)) ? 0 : item.old_consumed_quantity }}</td>
                                            <td>
                                                <input v-if="status == 'Issued' && can('create-requisition') && parseInt(item.issued_quantity) > parseInt(item.old_consumed_quantity)" type="number" onfocus="this.select()" :max="parseInt(item.issued_quantity) - parseInt(item.old_consumed_quantity)" min="0" v-model="item.consumed_quantity" class="form-control input-sm" number>
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
                                <input type="submit" name="approve" class="btn btn-success" @click.prevent="approve()" value="Approve Requisition">
                                <input type="submit" name="disapprove" class="btn btn-danger" @click.prevent="disapprove()" value="Disapprove Requisition">
                            </span>
                            <span v-if="(status == 'Approved' && can('issue-requisition'))">
                                <input type="submit" name="issue" class="btn btn-success" @click.prevent="approve()" value="Issue Parts">
                            </span>
                            <span v-if="status == 'Issued' && can('create-requisition')">
                                <input type="submit" name="consumption" class="btn btn-success" @click.prevent="consume()" value="Update Card">
                            </span>
                            <a href="#" @click.prevent="back()" class="btn btn-danger">Back</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                printout: '',
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
                let selected = this.vehicles.filter((item) => (item.id == this.card.vehicle_id));
                selected = selected.length ? selected[0] : { driver: {} };
                selected.driver = selected.driver ? selected.driver : { name: 'No Driver' };

                return selected;
            },

            jobTypes() {
                let selected = this.job_types.filter((t) => (t.service_type == this.card.service_type));

                return selected.length ? selected : [];
            },

            jobType() {
                let selected = this.job_types.filter((item) => item.id == this.card.workshop_job_type_id);
                selected = selected.length ? selected[0] : { operations: [] };

                return selected;
            },

            operation() {
                let selected = this.jobType.operations.filter((item) => item.id == this.task.operation_id);
                selected = selected.length ? selected[0] : { tasks: [] };
                selected.tasks = selected.tasks ? selected.tasks : [];

                return selected;
            },
        },

        created() {
            this.checkState();
        },

        methods: {
          back() {
            window._router.go(-1);
          },

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
                            item.approved_quantity = parseInt(item.approved_quantity);
                            item.old_approved = item.approved_quantity;
                            item.old_issued = item.issued_quantity;
                            item.actual_old_issued = isNaN(parseInt(item.old_issued)) ? 0 : parseInt(item.old_issued);
                            item.approved_quantity = 'Pending';
                            item.issued_quantity = 'Pending';
                            let issued = isNaN(parseInt(item.old_issued)) ? 0 : parseInt(item.old_issued);
                            item.issued_quantity = this.status == 'Approved' ? item.approved_quantity : item.issued_quantity;

                            if (this.status == 'Pending Approval') {
                                item.approved_quantity = item.requested_quantity;

                                return item;
                            }

                            if (this.status == 'Approved') {
                                item.approved_quantity = item.old_approved;
                                item.issued_quantity = item.approved_quantity - issued;

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
                if (this.$route.params.issue) {
                    this.requisition.is_issue = true;
                }

                http.post('/api/parts/' + this.$route.params.id + '/approve?station='+window.Laravel.station_id, this.requisition).then((response) => {
                    this.printout = response.printout;
                    this.$root.isLoading = false;
                    setTimeout(() => {
                        window.print();
                    }, 200);
                    setTimeout(() => {
                        alert2(this.$root, [response.message], 'success');
                        window._router.go(-1);
                    }, 1000);
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },


            consume() {
                this.$root.isLoading = true;
                this.requisition.type = 'consumption';
                http.post('/api/parts/' + this.$route.params.id + '/consume?station='+window.Laravel.station_id, this.requisition).then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    this.$root.isLoading = false;
                    window._router.push({ path: '/wsh/parts' });
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
                    window._router.push({ path: '/wsh/parts' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
