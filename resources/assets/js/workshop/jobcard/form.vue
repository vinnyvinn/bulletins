<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>New Job Card</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="service_type">Job/Service</label>
                                <select :disabled="editing" required v-model="card.service_type" name="service_type" id="service_type" class="form-control">
                                    <option value="Normal Job">Normal Job</option>
                                    <option value="Service Job">Service Job</option>
                                </select>
                            </div>


                            <div class="form-group input-group-sm">
                                <label for="workshop_job_type_id">Job Type</label>
                                <select :disabled="editing" required required v-model="card.workshop_job_type_id" name="workshop_job_type_id" id="workshop_job_type_id" class="form-control">
                                    <option v-for="type in jobTypes" :value="type.id">{{ type.name }}</option>
                                </select>
                            </div>


                            <div class="form-group input-group-sm">
                                <label for="job_description">Job Description</label>
                                <textarea :disabled="editing" required v-model="card.job_description" name="job_description" id="job_description" cols="20" rows="5" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Vehicle/Chassis Number</label>
                                <h4 v-if="editing"><strong>{{ card.vehicle_number }}</strong></h4>
                                <select v-else :disabled="editing" required v-model="card.vehicle_id" name="vehicle_id" id="vehicle_id" class="form-control select2">
                                    <option v-for="vehicle in vehicles" :value="vehicle.id">{{ vehicle.plate_number }}</option>
                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="expected_completion">Expected Completion Date</label>
                                <input required type="text" v-model="card.expected_completion" name="expected_completion" id="expected_completion" class="form-control datepicker">
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Driver</label>
                                <h4><strong>{{ vehicle.driver.first_name }} {{ vehicle.driver.last_name }}, {{ vehicle.driver.mobile_phone }}</strong></h4>
                            </div>

                        </div>


                        <div class="col-sm-4">
                            <div class="form-group input-group-sm">
                                <label for="vehicle_id">Make &amp; Model</label>
                                <h4><strong>{{ vehicle.make.name }}, {{ vehicle.model.name }}</strong></h4>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="time_in">Time In</label>
                                <input :disabled="editing" required type="time" v-model="card.time_in" name="time_in" id="time_in" class="form-control">
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="current_km_reading">Current KM Reading</label>
                                <input :disabled="status != 'Pending Approval' && status != null" required type="number" v-model="card.current_km_reading" name="current_km_reading" id="current_km_reading" class="form-control">
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="fuel_balance">Fuel Balance</label>
                                <input :disabled="status != 'Pending Approval' && status != null" required type="text" v-model="card.fuel_balance" name="fuel_balance" id="fuel_balance" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Inspection</th>
                                    <th>Done By</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in card.inspections">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ item.inspection_name }}</td>
                                    <td>
                                        <select :disabled="status != 'Pending Approval' && status != null" required v-model="item.employee_id" class="form-control input-sm">
                                            <option v-for="employee in employees" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select v-model="item.status" class="form-control input-sm">
                                            <option value="Not Started">Not Started</option>
                                            <option value="In Progress">In Progress</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="mechanic_findings">Mechanic's Findings</label>
                                <textarea required :disabled="status != 'Pending Approval' && status != null" v-model="card.mechanic_findings" name="mechanic_findings" id="mechanic_findings" cols="20" rows="5" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{status}}<!--
                    v-if="status == 'Pending Approval' || status == null"-->
                    <div class="row" >
                        <div class="col-sm-5">
                            <div class="form-group input-group-sm">
                                <label for="operation_id">Operation Name</label>
                                <select v-model="task.operation_id" name="operation_id" id="operation_id" class="form-control">
                                    <option v-for="operation in jobType.operations" :value="operation.id">{{ operation.name }}</option>
                                </select>
                            </div>

                            <div class="form-group input-group-sm">
                                <label for="employee_id">Assigned To</label>
                                <select v-model="task.employee_id" name="employee_id" id="employee_id" class="form-control">
                                    <option v-for="employee in employees" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group input-group-sm">
                                <label for="workshop_job_task_id">Tasks</label>
                                <input type="text" name="workshop_job_task_id" v-model="workshop_job_type_text" class="form-control" />
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group input-group-sm">
                                        <label for="task_start_date">Start Date</label>
                                        <input type="text" v-model="task.start_date" name="task_start_date" id="task_start_date" class="form-control datepicker">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group input-group-sm">
                                        <label for="task_start_time">Time In</label>
                                        <input type="time" v-model="task.start_time" name="task_start_time" id="task_start_time" class="form-control">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-2">
                            <br>
                            <br>
                            <a @click="addTask" class="btn btn-success btn-block">Add Task</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>Operation</th>
                                        <th>Action</th>
                                        <th>Allocated To</th>
                                        <th>Start Date</th>
                                        <th>Start Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="task in card.tasks">
                                        <td>{{ task.operation }}</td>
                                        <td>{{ task.task_name }}</td>
                                        <td>
                                            <select v-model="task.employee_id" class="form-control input-sm">
                                                <option v-for="employee in employees" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                                            </select>
                                        </td>
                                        <td>{{ task.start_date }}</td>
                                        <td>{{ task.start_time }}</td>
                                        <td>
                                            <select v-model="task.status" class="form-control input-sm">
                                                <option value="Not Started">Not Started</option>
                                                <option value="In Progress">In Progress</option>
                                                <option value="Completed">Completed</option>
                                            </select>
                                        </td>
                                        <td>
                                            <a v-if="status == 'Pending Approval' || status == null" @click="removeTask(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <!--<button v-if="status == null || status == 'Saved'" class="btn btn-success" @click.prevent="saveProcess">Save</button>-->
                        <button class="btn btn-success">{{ editing ? 'Save' : 'Process' }}</button>
                        <!-- <button v-if="status == 'Approved'" class="btn btn-warning" @click.prevent="closeCard">Close Job Card</button> -->
                        <router-link to="/wsh/job-card" class="btn btn-danger">Back</router-link>
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
                status: null,
                editing: false,
                vehicles: [],
                job_types: [],
                employees: [],
                workshop_job_type_text: '',
                task: {
                    operation_id: '',
                    workshop_job_task_id: '',
                    workshop_job_task_name: '',
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
                }
            };
        },

        computed: {
            vehicle() {
                let selected =  this.vehicles.filter((item) => (item.id == this.card.vehicle_id));
                selected = selected.length ? selected[0]: { driver:{}, make: {}, model: {} };
                selected.driver = selected.driver ? selected.driver : { name: 'No Driver' };

                return selected;
            },

            jobTypes() {
                let selected = this.job_types.filter((t) => (t.service_type == this.card.service_type));

                return selected.length ? selected : [];
            },

            jobType() {
                let selected =  this.job_types.filter((item) => item.id == this.card.workshop_job_type_id);
                selected = selected.length ? selected[0]: { name: '', operations:[] };

                if (selected.name.indexOf('Service') !== -1) {
                  this.setupTasks(selected);
                }

                return selected;
            },

            operation() {
                let selected =  this.jobType.operations.filter((item) => item.id == this.task.operation_id);
                selected = selected.length ? selected[0]: { tasks:[] };
                selected.tasks = selected.tasks ? selected.tasks : [];

                return selected;
            },

            filteredTasks() {
                let selected = this.card.tasks.map(e => parseInt(e.workshop_job_task_id));
                if (! this.operation.tasks) return [];

                return this.operation.tasks.filter(e => selected.indexOf(parseInt(e.id)) === -1);
            },
        },

        created() {
            this.$root.isLoading = true;
            http.get('/api/job-card/create').then((response) => {
                console.log("response is", response);
                this.vehicles = response.vehicles;
                this.job_types = response.job_types;
                this.employees = response.employees;
                this.createCheckLists(response.checklist);
                setTimeout(() => {
                    let dateSettings = {
                        format: 'yyyy-mm-dd',
                        startDate: '+0d',
                        autoclose: true,
                        todayHighlight: true,
                    };

                    $('#expected_completion').datepicker(dateSettings).on('change', (e) => {
                        this.card.expected_completion = e.target.value;
                    });

                    $('#task_start_date').datepicker(dateSettings).on('change', (e) => {
                        this.task.start_date = e.target.value;
                    });
                    $('#vehicle_id').select2().on('change', (e) => {
                        this.card.vehicle_id = e.target.value;
                    });
                    this.$root.isLoading = false;
                }, 500);

                return response;
            }).then(e => this.checkState());
        },

        methods: {
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

            setupTasks(type) {
              this.card.tasks = [];
              let operation = type.operations[0];
              let date = (new Date()).toLocaleDateString('en-GB');
              date = date.split('/');
              date = date[2] + '-' + date[1] + '-' + date[0];

              operation.tasks.map(item => {
                this.card.tasks.push({
                    operation_id: operation.id,
                    operation: operation.name,
                    workshop_job_task_id: item.id,
                    task_name: item.name,
                    employee_id: '',
                    start_date: date,
                    start_time: '08:00',
                    status: 'Not Started'
                });
              });
            },

            addTask() {
                if (! this.task.operation_id || this.task.operation_id.length == 0) return alert2(this.$root, ['Select the operation'], 'danger');
                if (! this.task.employee_id || this.task.employee_id.length == 0) return alert2(this.$root, ['Select the employee'], 'danger');
                if (! this.task.start_date || this.task.start_date.length == 0) return alert2(this.$root, ['Enter the start date'], 'danger');
               // if (! this.task.workshop_job_task_id || this.task.workshop_job_task_id.length == 0) return alert2(this.$root, ['Select the task'], 'danger');
                if (! this.workshop_job_type_text) return alert2(this.$root, ['Task should not be empty'], 'danger');



                this.task.operation = this.operation.name;

                //no longer pulling data from a dropdownlist
                this.task.workshop_job_task_id = 1;

                this.task.task_name = this.workshop_job_type_text;
                this.task.workshop_job_task_name = this.workshop_job_type_text; //save to db an id after save

                //get the task name from db
                //this.task.workshop_job_task_id == e.id

/*
                this.task.task_name = this.operation.tasks.filter((e) => {
                    return this.task.workshop_job_task_id == e.id;
                })[0].name;

*/


  /*
*/


                this.card.tasks.push(this.task);


                this.task = {
                    operation_id: '',
                    workshop_job_task_id: '',
                    employee_id: '',
                    start_date: '',
                    start_time: '08:00',
                    status: 'Not Started'
                };
            },

            removeTask(task) {
                this.card.tasks.splice(this.card.tasks.indexOf(task), 1);
            },

            checkState() {
                if (this.$route.params.id) {
                    this.editing = true;
                    http.get('/api/job-card/' + this.$route.params.id).then((response) => {
                        this.card = response.card.raw_data;
                        this.status = response.card.status;
                    });
                }
            },

            store() {
                let request = null;
                this.card.vehicle_number = this.vehicle.plate_number;

                if (this.$route.params.id) {
                    request = http.put('/api/job-card/' + this.$route.params.id, this.card);
                } else {
                    request = http.post('/api/job-card', this.card);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/wsh/job-card' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });



            },

            saveProcess() {
                let request = null;
                this.card.vehicle_number = this.vehicle.plate_number;
                request = http.put('/api/job-card/' + this.$route.params.id, this.card);

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/wsh/job-card' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },

            closeCard() {
                let request = null;
                this.card.vehicle_number = this.vehicle.plate_number;
                request = http.post('/api/job-card/' + this.$route.params.id + '/close', this.card);

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/wsh/job-card' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
