<template>
    <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-body">
                <strong v-if="showTrucks">Trucks Allocation: You can allocate a maximum of {{ contract_trucks.trucks_allocated }} trucks to this contract.</strong>
                <strong v-if="!showTrucks">Employee Allocation:</strong>
                <button v-if="showTrucks" type="button" name="button" class="btn btn-success btn-small pull-right" @click="showTrucks = !showTrucks">Assign Employees</button>
                <button v-if="!showTrucks" type="button" name="button" class="btn btn-success btn-small pull-right" @click="showTrucks = !showTrucks">Assign Trucks</button>
              </div>
            </div>
          </div>
        </div>
        <div class="row" v-if="showTrucks">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Trucks Available</strong>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="truck in trucks">
                                    <td>{{ truck.plate_number }}</td>
                                    <td v-if="truck.trailer">{{ truck.trailer.trailer_number }}</td>
                                    <td v-if="!truck.trailer"> - </td>
                                    <td v-if="truck.driver">{{ truck.driver.first_name }}</td>
                                    <td><button type="button" @click="allocate(truck)" name="button" class="btn btn-sm btn-success">Add</button></td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                  <th>Plate #</th>
                                  <th>Trailer</th>
                                  <th>Driver</th>
                                  <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Allocated Trucks</strong>
                        <button type="button" name="button" class="btn btn-success btn-sm pull-right" @click="store">Save Allocation</button>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Plate #</th>
                                    <th>Trailer</th>
                                    <th>Driver</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="allocatedtruck in allocation.allocatedtrucks">
                                    <td>{{ allocatedtruck.plate_number }}</td>
                                    <td v-if="allocatedtruck.trailer">{{ allocatedtruck.trailer.trailer_number}}</td>
                                    <td v-if="!allocatedtruck.trailer"> - </td>
                                    <td v-if="allocatedtruck.driver">{{ allocatedtruck.driver.first_name }}</td>
                                    <td><button type="button" @click="remove(allocatedtruck)" name="button" class="btn btn-sm btn-danger">Remove</button></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="!showTrucks">
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Employees Allocation
              </div>
              <div class="panel-body">
                <table class="table no-wrap">
                  <caption>Supervisors</caption>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Employee</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="employee in employees" v-if="employee.designation == 'supervisor'">
                      <td>{{ employee.id }}</td>
                      <td>{{ employee.name }}</td>
                      <td><button type="button" @click="allocateEmployee(employee)" name="button" class="btn btn-sm btn-success">Add</button></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
                <hr>
                <table class="table no-wrap">
                  <caption>Casuals</caption>
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Employee</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="employee in employees" v-if="employee.designation == 'casual'">
                      <td>{{ employee.id }}</td>
                      <td>{{ employee.name }}</td>
                      <td><button type="button" @click="allocateEmployee(employee)" name="button" class="btn btn-sm btn-success">Add</button></td>
                      <td></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-default">
              <div class="panel-heading">
                Allocated Employees
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                    <table class="table no-wrap">
                      <caption>Supervisors</caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="allocatedEmployee in employeeAllocation.allocatedEmployees" v-if="allocatedEmployee.designation == 'supervisor'">
                            <td>{{ allocatedEmployee.id }}</td>
                            <td>{{ allocatedEmployee.name }}</td>
                            <td><button type="button" @click="removeEmployee(allocatedEmployee)" name="button" class="btn btn-sm btn-danger">Remove</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table no-wrap">
                      <caption>Casuals</caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="allocatedEmployee in employeeAllocation.allocatedEmployees" v-if="allocatedEmployee.designation == 'casual'">
                            <td>{{ allocatedEmployee.id }}</td>
                            <td>{{ allocatedEmployee.name }}</td>
                            <td><button type="button" @click="removeEmployee(allocatedEmployee)" name="button" class="btn btn-sm btn-danger">Remove</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
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
                contract_trucks: {},
                showTrucks: true,
                trucks: [],
                allocation: {
                  allocatedtrucks: [],
                  contract_id: ''
                },
                employees: [
                  {
                    id: 1,
                    name: 'Frodo Baggins',
                    designation: 'supervisor'
                  },
                  {
                    id: 2,
                    name: 'Luke Skywalker',
                    designation: 'supervisor'
                  },
                  {
                    id: 3,
                    name: 'Darth Vader',
                    designation: 'casual'
                  },
                  {
                    id: 4,
                    name: 'Kylo Ren',
                    designation: 'casual'
                  },
                  {
                    id: 5,
                    name: 'Clone Trooper',
                    designation: 'casual'
                  }
                ],
                employeeAllocation: {
                  allocatedEmployees: []
                }
            };
        },
        created() {
          http.get('/api/journey/create').then( response => {
            this.allocation.contract_id = this.$route.params.id;
            this.trucks = response.trucks;
            // this.employees = response.employees;
          });
          http.get('/api/contract-trucks/' + this.$route.params.id).then( response => {
            this.contract_trucks = response.contract_trucks;
          });
        },
        mounted () {
          setTimeout(() => {
              $('#supervisor').select2().on('change', e => this.employeeAllocation.supervisor = e.target.value);
          }, 1000);
        },
        methods: {
            allocate (truck) {
              if(this.allocation.allocatedtrucks >= this.contract_trucks) {
                alert2(this.$root, ['Maximum Trucks for contract reached. Remove an existing truck to allow further allocation'], 'danger');
                return;
              } else {
                for(var i=0; i < this.trucks.length; i++) {
                   if(this.trucks[i].id == truck.id)
                   {
                      this.trucks.splice(i,1);
                   }
                }
                this.allocation.allocatedtrucks.push(truck);
              }
            },

            allocateEmployee (employee) {
              for(var i=0; i < this.employees.length; i++) {
                 if(this.employees[i].id == employee.id)
                 {
                    this.employees.splice(i,1);
                 }
              }
              this.employeeAllocation.allocatedEmployees.push(employee);
            },

            remove (allocatedtruck) {
              for(var i=0; i < this.allocation.allocatedtrucks.length; i++) {
                 if(this.allocation.allocatedtrucks[i].id == allocatedtruck.id)
                 {
                    this.allocation.allocatedtrucks.splice(i,1);
                 }
              }
              this.trucks.push(allocatedtruck);
            },

            removeEmployee (allocatedEmployee) {
              for(var i=0; i < this.employeeAllocation.allocatedEmployees.length; i++) {
                 if(this.employeeAllocation.allocatedEmployees[i].id == allocatedEmployee.id)
                 {
                    this.allocation.allocatedEmployees.splice(i,1);
                 }
              }
              this.employees.push(allocatedEmployee);
            },

            store () {
              http.post('/api/allocate_truck', this.allocation).then( response => {
                alert2(this.$root, [response.message], 'success');
                this.$router.push('/ls/trucks-allocation/' + this.allocation.contract_id);
              });

            },


            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },



            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/journey/' + id + '/?s=' + window.Laravel.station_id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
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
