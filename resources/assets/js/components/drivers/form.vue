<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Driver Details</strong>
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
                                <input v-model="driver.first_name" type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input v-model="driver.last_name" type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>

                            <div class="form-group">
                                <label for="identification_type">Identification Type</label>
                                <select v-model="driver.identification_type" class="form-control" id="identification_type" name="identification_type" required>
                                    <option value="National ID">National ID</option>
                                    <option value="Passport">Passport</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="identification_number">National ID</label>
                                <input v-model="driver.identification_number" type="text" class="form-control" id="identification_number" name="identification_number" required>
                            </div>

                            <div class="form-group">
                                <label for="dl_number">Drivers License Number</label>
                                <input v-model="driver.dl_number" type="text" class="form-control text-uppercase" id="dl_number" name="dl_number">
                            </div>

                            <div class="form-group">
                                <label for="mobile_phone">Mobile Number</label>
                                <input v-model="driver.mobile_phone" type="text" class="form-control" id="mobile_phone" name="mobile_phone">
                            </div>

                            <udf module="Drivers" :state="driver" :uploads="uploads"></udf>

                            <div class="form-group">
                                <button class="btn btn-success">Save</button>
                                <router-link to="/drivers" class="btn btn-danger">Back</router-link>
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
        mounted() {
            this.checkState();
        },
        data() {
            return {
                sharedState: window._mainState,
                uploads: [],
                hremployees:[],
                driver: {
                    _token: window.Laravel.csrfToken,
                    _method: 'POST',
                    payroll_number: '',
                    identification_number: '',
                    identification_type: 'National ID',
                    first_name: '',
                    last_name: '',
                    email: '',
                    dl_number: '',
                    mobile_phone: ''
                },
                errors: [],
                level: 'danger',
                showError: false
            };
        },
        created() {
            this.$root.isLoading = true;
            http.get('/api/employee_category'). then((response) => {
                const byName = response.hremployees.slice(0);
                this.hremployees =  byName.sort(function(a,b) {
                    const x = a.Emp_First_Name.toLowerCase();
                    const y = b.Emp_First_Name.toLowerCase();
                    return x < y ? -1 : x > y ? 1 : 0;
                });

                $('#hr_employee').select2().on('change', (e) => {
                    var employee = this.hremployees.filter(employee=>employee.Emp_Payroll_No === e.target.value);
                    if(employee.length >0){
                        const emp = employee[0];
                        this.driver.payroll_number = emp.Emp_Payroll_No;
                        this.driver.identification_number = emp.ICardNo;
                        this.driver.payroll_number = emp.Emp_Payroll_No;
                        this.driver.last_name = emp.Emp_Last_Name;
                        this.driver.first_name = emp.Emp_First_Name;
                        this.driver.identification_type='National ID';
                    }
                    console.log("value is ", employee[0]);
                });
                this.$root.isLoading = false;
            });
        },

        methods: {
            checkState() {
                if (this.$route.params.id) {
                    this.driver._method = 'PUT';
                    http.get('/api/driver/' + this.$route.params.id).then((response) => {
                        this.driver = response.driver;
                    });
                }
            },

            store() {
                this.$root.isLoading = true;
                let request = null;
                let data = mapToFormData(this.driver, this.uploads, typeof this.$route.params.id === 'string');

                if (this.$route.params.id) {
                    request = http.put('/api/driver/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/driver', data, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/drivers' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
