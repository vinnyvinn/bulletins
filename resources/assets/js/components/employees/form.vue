<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Employee Details</strong>
                        <div class="pull-right">
                          <router-link to="/employee_category/create" class="btn btn-danger">New Category</router-link>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form action="#" id="form" role="form" @submit.prevent="store" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input v-model="employee.first_name" type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input v-model="employee.last_name" type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>

                            <div class="form-group">
                                <label for="identification_type">Identification Type</label>
                                <select v-model="employee.identification_type" class="form-control" id="identification_type" name="identification_type" required>
                                    <option value="National ID">National ID</option>
                                    <option value="Passport">Passport</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="identification_type">Employee Category</label>
                                <select v-model="employee.category" class="form-control" id="category" name="category" required>
                                    <option value="category.category" v-for="category in employee_categories">{{ category.category }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="identification_number">National ID</label>
                                <input v-model="employee.identification_number" type="text" class="form-control" id="identification_number" name="identification_number" required>
                            </div>

                            <div class="form-group">
                                <label for="mobile_phone">Mobile Number</label>
                                <input v-model="employee.mobile_phone" type="text" class="form-control" id="mobile_phone" name="mobile_phone">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/employees" class="btn btn-danger">Back</router-link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {

        data() {
            return {
                sharedState: window._mainState,
                uploads: [],
                employee: {
                    _token: window.Laravel.csrfToken,
                    _method: 'POST',
                    payroll_number: '',
                    identification_number: '',
                    identification_type: 'National ID',
                    first_name: '',
                    last_name: '',
                    email: '',
                    dl_number: '',
                    mobile_phone: '',
                    category: ''
                },
                employee_categories: [],
                errors: [],
                level: 'danger',
                showError: false
            };
        },

        created() {

          this.$root.isLoading = true;
          http.get('/api/employee_category'). then((response) => {
            this.employee_categories = response.employee_categories;
            this.$root.isLoading = false;
          });

          if(this.$route.params.id) {
            this.$root.isLoading = true;
            http.get('/api/employee/' + this.$route.params.id).then( (response) => {
              this.employee = response.employee;
              this.$root.isLoading = false;
            });
          }


        },


        methods: {

            store() {
              http.post('/api/employee', this.employee).then((response) => {
                alert2(this.$root,[response.message], 'success');
              });
              window._router.push({path: '/employees'});
            },
        }
    }
</script>
