<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Employee Details</strong>
                        <div class="pull-right">
                          <router-link to="/employee_category/create" class="btn btn-primary btn-xs">New Category</router-link>
                        </div>
                    </div>

                    <div class="panel-body">
                        <form action="#" id="form" role="form" @submit.prevent="store" enctype="multipart/form-data">

                           <div>
                               <label for="first_name">Select from HR to autofill</label>
                               <select id="hr_employee" class="form-control select2">
                                   <option value-="null">Select ....</option>
                                   <option v-for="employee in hremployees" :value="employee.Emp_Payroll_No">{{ employee.Emp_First_Name }} {{ employee.Emp_Last_Name }}</option>
                               </select>
                           </div>


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
                                    <option :value="category.category" v-for="category in employee_categories">{{ category.category }}</option>
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
                hremployees:[],
                errors: [],
                level: 'danger',
                showError: false
            };
        },

        created() {

          this.$root.isLoading = true;
          http.get('/api/employee_category'). then((response) => {
            this.employee_categories = response.employee_categories;
            //order response of hr employees

              const byName = response.hremployees.slice(0);
              this.hremployees =  byName.sort(function(a,b) {
                  const x = a.Emp_First_Name.toLowerCase();
                  const y = b.Emp_First_Name.toLowerCase();
                  return x < y ? -1 : x > y ? 1 : 0;
              });

              //this.hremployees = response.hremployees;
              $('#hr_employee').select2().on('change', (e) => {
                  var employee = this.hremployees.filter(employee=>employee.Emp_Payroll_No === e.target.value);
                  if(employee.length >0){
                      const emp = employee[0];
                      this.employee.payroll_number = emp.Emp_Payroll_No;
                      this.employee.identification_number = emp.ICardNo;
                      this.employee.payroll_number = emp.Emp_Payroll_No;
                      this.employee.last_name = emp.Emp_Last_Name;
                      this.employee.first_name = emp.Emp_First_Name;
                      this.employee.identification_type='National ID';
                  }
                  console.log("value is ", employee[0]);
              });
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
                let request = null;
                if (this.$route.params.id) {
                    request = http.put('/api/employee/' + this.$route.params.id, this.employee)
                } else {
                    request = http.post('/api/employee', this.employee)
                }
                request.then((response) => {
                    alert2(this.$root,[response.message], 'success');
                });
                window._router.push({path: '/employees'});
            },
        }
    }
</script>
