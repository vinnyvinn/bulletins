<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Progress Report</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">

                  <div class="row">
                    <div class="col-sm-12">
                      <div class="form-group">
                        <label for="update">Progress Update</label>
                        <textarea required v-model="update.update" name="update" rows="5" class="form-control"></textarea>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="employee_id">Handled By</label>
                        <select v-model="update.employee_id" required class="form-control input-sm" name="employee_id" id="employee_id">
                          <option v-for="employee in employees" :value="employee.id">{{ employee.first_name }} {{ employee.last_name }}</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="job_status">Status</label>
                        <select required id="job_status" v-model="update.job_status" class="form-control input-sm">
                            <option value="Not Started">Not Started</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="job_depends_on">Current status depends on</label>
                        <input class="form-control input-sm" type="text" id="job_depends_on" v-model="update.job_depends_on">
                      </div>
                    </div>
                  </div>

                    <div class="form-group">
                        <button class="btn btn-success">Save</button>
                        <router-link to="/wsh/job-card/open" class="btn btn-danger">Back</router-link>
                    </div>
                </form>

                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Handled By</th>
                          <th>Update</th>
                          <th>Status</th>
                          <th>Status Held By</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="update in updates">
                          <td>{{ update.created }}</td>
                          <td>{{ update.employee.first_name }}{{ update.employee.last_name }}</td>
                          <td>{{ update.update }}</td>
                          <td>{{ update.job_status }}</td>
                          <td>{{ update.job_depends_on }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                updates: [],
                employees: [],
                update: {
                    job_card_id: null,
                    employee_id: null,
                    update: '',
                    job_status: '',
                    job_depends_on: ''
                }
            };
        },

        created() {
            this.$root.isLoading = true;
            http.get('/api/job-card-progress/create?id=' + this.$route.params.id).then((response) => {
                this.employees = response.employees;
                this.updates = response.updates;
                this.update.job_card_id = this.$route.params.id;
                this.$root.isLoading = false;

                return response;
            });
        },

        methods: {
            store() {
                let request = null;

                // if (this.$route.params.id) {
                //     request = http.put('/api/job-card-progress/' + this.$route.params.id, this.update);
                // } else {
                    request = http.post('/api/job-card-progress', this.update);
                // }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/wsh/job-card/open' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
