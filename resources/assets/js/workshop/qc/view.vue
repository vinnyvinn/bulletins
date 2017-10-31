<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>QC for Job Card #JC-{{ card_number }}</strong>

                <a :href="'/job-card/print/' + card_number" class="btn btn-xs btn-primary pull-right" target="_blank">PRINT</a>
            </div>

            <div class="panel-body">
                <form action="#" role="form">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="service_type">Job/Service</label>
                                <select disabled required v-model="card.service_type" name="service_type" id="service_type" class="form-control">
                                    <option value="Normal Job">Normal Job</option>
                                    <option value="Service Job">Service Job</option>
                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="workshop_job_type_id">Job Type</label>
                                <select disabled required v-model="card.workshop_job_type_id" name="workshop_job_type_id" id="workshop_job_type_id" class="form-control">
                                    <option v-for="type in jobTypes" :value="type.id">{{ type.name }}</option>
                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="job_description">Job Description</label>
                                <textarea disabled required v-model="card.job_description" name="job_description" id="job_description" cols="20" rows="5" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Vehicle/Chassis Number</label>
                                <select disabled required v-model="card.vehicle_id" name="vehicle_id" id="vehicle_id" class="form-control select2">
                                    <option v-for="vehicle in vehicles" :value="vehicle.id">{{ vehicle.plate_number }}</option>
                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Driver</label>
                                <h5>
                                    <strong>{{ vehicle.driver.first_name }} {{ vehicle.driver.last_name }}, {{ vehicle.driver.mobile_phone }}</strong>
                                </h5>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="mechanic_findings">Mechanic's Findings</label>
                                <textarea disabled v-model="card.mechanic_findings" name="mechanic_findings" id="mechanic_findings" cols="20" rows="5" class="form-control input-sm"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Make &amp; Model</label>
                                <h5>
                                    <strong>{{ vehicle.make.name }}, {{ vehicle.model.name }}</strong>
                                </h5>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="time_in">Time In</label>
                                <input disabled required type="time" v-model="card.time_in" name="time_in" id="time_in" class="form-control">
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="current_km_reading">Current KM Reading</label>
                                <input disabled type="number" v-model="card.current_km_reading" name="current_km_reading" id="current_km_reading" class="form-control">
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="fuel_balance">Fuel Balance</label>
                                <input disabled type="text" v-model="card.fuel_balance" name="fuel_balance" id="fuel_balance" class="form-control">
                            </div>

                        </div>
                    </div>


                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                          <h4><strong>Quality Check</strong></h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Operation</th>
                                            <th>Action</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="task in card.tasks">
                                            <td>{{ task.operation }}</td>
                                            <td>{{ task.task_name }}</td>
                                            <td>
                                                <select disabled v-model="task.status" class="form-control input-sm">
                                                    <option value="Accepted">Accepted</option>
                                                    <option value="Rejected">Rejected</option>
                                                    <option value="Waivered">Waivered</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                            <label for="closing_remarks">Remarks</label>
                            <textarea disabled v-model="qc.remarks" name="closing_remarks" id="closing_remarks" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <button @click.prevent="process('Approve')" v-if="qc.status == 'Pending Approval'" class="btn btn-success" type="submit">Approve</button>
                      <button @click.prevent="process('Disapprove')" v-if="qc.status == 'Pending Approval'" class="btn btn-danger" type="submit">Disapprove</button>
                      <button @click.prevent="process('Waiver')" v-if="qc.status == 'Pending Approval'" class="btn btn-primary" type="submit">Waiver</button>
                      <router-link to="/wsh/qc" class="btn btn-danger">Back</router-link>
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
                card_number: '',
                user: {},
                requested_on: '',
                vehicles: [],
                job_types: [],
                employees: [],
                task: {
                    operation_id: '',
                    workshop_job_task_id: '',
                    employee_id: '',
                    start_date: '',
                    start_time: '08:00',
                    status: 'Not Started'
                },
                card: {
                    service_type: 'Normal Job',
                    vehicle_id: '',
                    workshop_job_type_id: '',
                    expected_completion: '',
                    time_in: '08:00',
                    job_description: '',
                    current_km_reading: '',
                    fuel_balance: '',
                    has_trailer: '',
                    inspections: [],
                    mechanic_findings: '',
                    tasks: [],
                    closing_remarks: '',
                },
                qc: {
                  job_card_id: null,
                  status: '',
                  tasks: null,
                  remarks: ''
                },
            };
        },

        computed: {
            vehicle() {
                let selected = this.vehicles.filter((item) => (item.id == this.card.vehicle_id));
                selected = selected.length ? selected[0] : { driver: {}, make: {}, model: {} };
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
            this.$root.isLoading = true;
            http.get('/api/job-card/create').then((response) => {
                this.vehicles = response.vehicles;
                this.job_types = response.job_types;
                this.employees = response.employees;
                this.createCheckLists(response.checklist);
                setTimeout(() => {
                    this.$root.isLoading = false;
                }, 500);

                return response;
            }).then(() => this.checkState());
        },


        methods: {
          initiateClose() {
            this.closing = true;
          },

          formatDate(date) {
              date = new Date(date);
              let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

              let month = months[date.getMonth()];
              let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

              return day + ' ' + month + ' ' + date.getFullYear();
          },
          createCheckLists(lists) {
              lists.forEach((item) => {
                  this.card.inspections.push({
                      workshop_inspection_check_list_id: item.id,
                      inspection_name: item.name,
                      employee_id: '',
                      status: 'Not Started',
                  });
              });
          },

          checkState() {
              if (this.$route.params.id) {
                  http.get('/api/qc/' + this.$route.params.id).then((response) => {
                      this.card = response.card.tasks;
                      this.card_number = response.card.job_card_id;
                      this.qc = response.card;
                  });
              }
          },

          store() {
              this.$root.isLoading = true;
              this.qc.job_card_id = this.card_number;
              this.qc.tasks = this.card;

              http.post('/api/qc', this.qc).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/qc' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          process(type) {
            if (type == 'Approve') return this.approve();
            if (type == 'Disapprove') return this.disapprove();
            if (type == 'Waiver') return this.waiver();
          },

          approve() {
              this.$root.isLoading = true;
              http.post('/api/qc/' + this.$route.params.id + '/approve', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/qc' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          disapprove() {
              this.$root.isLoading = true;
              http.post('/api/qc/' + this.$route.params.id + '/disapprove', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/qc' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          waiver() {
              this.$root.isLoading = true;
              http.post('/api/qc/' + this.$route.params.id + '/waiver', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/qc' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },
        }
    }
</script>
