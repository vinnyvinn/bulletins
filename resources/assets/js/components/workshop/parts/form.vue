<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>New Parts Request</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">

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
                                        <select v-model="item.employee_id" class="form-control input-sm">
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
                                <textarea v-model="card.mechanic_findings" name="mechanic_findings" id="mechanic_findings" cols="20" rows="5" class="form-control input-sm"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
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
                                <label for="workshop_job_task_id">Task</label>
                                <select v-model="task.workshop_job_task_id" name="workshop_job_task_id" id="workshop_job_task_id" class="form-control">
                                    <option v-for="task in operation.tasks" :value="task.id">{{ task.name }}</option>
                                </select>
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
                                        <td><a @click="removeTask(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-success">Save</button>
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
                parts: [],
                cards: [],
                item: {
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
                }
            };
        },

        computed: {

        },

        created() {
            this.$root.isLoading = true;
            http.get('/api/parts/create').then((response) => {
                this.vehicles = response.vehicles;
                this.job_types = response.job_types;
                this.employees = response.employees;
                this.createCheckLists(response.checklist);
                setTimeout(() => {
                    let dateSettings = {
                        format: 'yyyy-mm-dd',
                        startDate: '+0d',
                        autoclose: true
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
                }, 1000);
            });
        },

        mounted() {
            this.checkState();
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

            addTask() {
                this.task.operation = this.operation.name;
                this.task.task_name = this.operation.tasks.filter((e) => {
                    return this.task.workshop_job_task_id == e.id;
                })[0].name;

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
                    http.get('/api/job-card/' + this.$route.params.id).then((response) => {
                        this.card = response.card.raw_data;
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
                    window._router.push({ path: '/job-card' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
