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
                                    <td> {{ truck.plate_number }} </td>
                                    <td> <span v-if="truck.trailer">{{ truck.trailer.plate_number }}</span></td>
                                    <td> <span v-if="truck.driver">{{ truck.driver.first_name }}</span></td>
                                    <td> <button v-if="$root.can('ls-create-allocation') || $root.can('ls-edit-allocation')" type="button" @click="allocate(truck)" name="button" class="btn btn-sm btn-success">Add</button></td>
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
                                    <td><span v-if="allocatedtruck.trailer"> {{ allocatedtruck.trailer.plate_number}} </span></td>
                                    <td><span v-if="allocatedtruck.driver"> {{ allocatedtruck.driver.first_name }} </span></td>
                                    <td>
                                      <button v-if="$root.can('ls-delete-allocation') || $root.can('ls-edit-allocation')" type="button" @click="toggleRemoveTruck(allocatedtruck)" class="btn btn-sm btn-danger"><i class="fa fa-minus"></i></button>
                                    </td>
                                </tr>
                                <tr v-if="unallocateTruck">
                                  <td colspan="2" class="text-left">Fuel Balance:</td>
                                  <td>
                                    <input type="number" class="form-control input-sm text-right" v-model="activeTruck.contract_end_fuel" min="0" onfocus="this.select()">
                                  </td>
                                  <td>
                                    <button type="button" @click.prevent="remove" class="btn btn-sm btn-success">Remove</button>
                                  </td>
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
                    <tr v-for="employee in employees" v-if="employee.category == 'supervisor'">
                      <td>{{ employee.id }}</td>
                      <td>{{ employee.first_name }} {{ employee.last_name}}</td>
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
                    <tr v-for="employee in employees" v-if="employee.category == 'casual'">
                      <td>{{ employee.id }}</td>
                      <td>{{ employee.first_name }} {{ employee.last_name}}</td>
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
                <strong>Allocated Employees</strong>
                <button type="button" name="button" class="btn btn-success btn-sm pull-right" @click="storeEmployee">Save Allocation</button>
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
                        <tr v-for="allocatedEmployee in employeeAllocation.allocatedEmployees" v-if="allocatedEmployee.category == 'supervisor'">
                            <td>{{ allocatedEmployee.id }}</td>
                            <td>{{ allocatedEmployee.first_name }} {{ allocatedEmployee.last_name }}</td>
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
                        <tr v-for="allocatedEmployee in employeeAllocation.allocatedEmployees" v-if="allocatedEmployee.category == 'casual'">
                            <td>{{ allocatedEmployee.id }}</td>
                            <td>{{ allocatedEmployee.first_name }} {{ allocatedEmployee.last_name }}</td>
                            <td>
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal">Open Modal</button>
                              <button type="button" @click="removeEmployee(allocatedEmployee)" name="button" class="btn btn-sm btn-danger">Remove</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Trigger the modal with a button -->
        <div id="myModal" class="modal fade" role="dialog" v-if="showModal">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Unallocate Truck ( <strong>{{ activeTruck.plate_number}} </strong>)</h4>
              </div>
              <div class="modal-body">
                <div class="row">

                  <form class="" action="#">
                    <div class="form-group col-sm-6">
                      <label for="fuel_reading">Fuel Reading</label>
                      <input type="text" id="fuel_reading" class="form-control input-sm" v-model="activeTruck.contract_end_fuel">
                    </div>
                    <div class="form-group col-sm-6">
                      <label for="mileage_reading">Truck Mileage Reading(Kms)</label>
                      <input type="text" id="mileage_reading" class="form-control input-sm" v-model="activeTruck.contract_end_mileage">
                    </div>

                  </form>
                </div>


              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" @click="unallocate()" name="button" class="btn btn-sm btn-danger">Remove Truck</button></td>
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
                activeTruck: {
                  contract_end_fuel: 0,
                },
                showModal: false,
                unallocateTruck: false,
                fuel_balance:0,
                contract_trucks: {},
                showTrucks: true,
                trucks: [],
                allocation: {
                  allocatedtrucks: [],
                  contract_id: ''
                },
                employees: [],
                employeeAllocation: {
                  contract_id: '',
                  allocatedEmployees: []
                }
            };
        },
        created() {
          if (! this.$route.params.id && ! this.$root.can('create-allocation')) {
              this.$router.push('/403');
              return false;
          }

          this.$root.isLoading = true;
          http.get('/api/journey/create').then( response => {
            this.allocation.contract_id = this.$route.params.id;
            this.trucks = response.trucks;
          });
          http.get('/api/unallocated_employees').then( response => {
            this.employeeAllocation.contract_id = this.$route.params.id;
            this.employees = response.employees;
          });
          http.get('/api/contract-trucks/' + this.$route.params.id).then( response => {
            this.contract_trucks = response.contract_trucks;
            this.employeeAllocation.allocatedEmployees = response.contract_employees;
            this.allocation.allocatedtrucks = response.allocated_trucks;
            this.$root.isLoading = false;
          });
        },
        mounted () {
          setTimeout(() => {
              $('#supervisor').select2().on('change', e => this.employeeAllocation.supervisor = e.target.value);
          }, 1000);
        },
        methods: {
            allocate (truck) {
              if(parseInt(this.allocation.allocatedtrucks.length) >= parseInt(this.contract_trucks.trucks_allocated)) {

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
            toggleRemoveTruck(truck){
              this.activeTruck = truck;
              return this.unallocateTruck = !this.unallocateTruck;
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

            remove () {
              var allocatedtruck = this.activeTruck;

              if(allocatedtruck.lsdelivery.length) {
                alert2(this.$root, ['This Truck has a delivery in progress. End delivery before un-allocating a truck'], 'danger');
                this.showModal = false;
                return this.unallocateTruck = false;
              }
              this.showModal = true;

              for(var i=0; i < this.allocation.allocatedtrucks.length; i++) {
                 if(this.allocation.allocatedtrucks[i].id == allocatedtruck.id)
                 {
                    this.allocation.allocatedtrucks.splice(i,1);
                 }
              }
               this.unallocateTruck = false;
              this.unallocate(allocatedtruck, this.currentFuelValue);
              this.trucks.push(allocatedtruck);
            },

            removeEmployee (allocatedEmployee) {
              for(var i=0; i < this.employeeAllocation.allocatedEmployees.length; i++) {
                 if(this.employeeAllocation.allocatedEmployees[i].id == allocatedEmployee.id)
                 {
                    this.employeeAllocation.allocatedEmployees.splice(i,1);
                 }
              }
              this.employees.push(allocatedEmployee);
            },

            store () {
              http.post('/api/allocate_truck', this.allocation).then( response => {
                if(response.status == 'error'){
                  alert2(this.$root, [response.message], 'danger');
                } else {
                  alert2(this.$root, [response.message], 'success');
                }
                this.$router.push('/ls/trucks-allocation/' + this.allocation.contract_id);
              });
            },

            storeEmployee () {
              http.post('/api/allocate_employee', this.employeeAllocation).then( response => {
                alert2(this.$root, [response.message], 'success');
                this.$router.push('/ls/trucks-allocation/' + this.allocation.contract_id);
              });

            },

            updateCurrentFuel(truck, fuelValue){
              http.post('', {truck: truck, fuelValue: fuelValue}).then(response=>{
              alert2(this.$root, [response.message], 'success');
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
            },

            unallocate() {
              this.$root.isLoading = true;
              this.showModal = false;
              http.post('/api/unallocate', this.activeTruck).then((response) => {
                this.activeTruck.contract_end_fuel = 0;
                alert2(this.$root, [response.message], 'success');
                this.$root.isLoading = false;
              });
            }
        }
    }
</script>

<style media="screen" scoped>

.modal-content {
  margin-top: 100px;
}
.modal-mask {
position: fixed;
z-index: 9998;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, .5);
display: table;
transition: opacity .3s ease;
}

.modal-wrapper {
display: table-cell;
vertical-align: middle;
}

.modal-container {
width: 300px;
margin: 0px auto;
padding: 20px 30px;
background-color: #fff;
border-radius: 2px;
box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
transition: all .3s ease;
font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
margin-top: 0;
color: #42b983;
}

.modal-body {
margin: 20px 0;
}

.modal-default-button {
float: right;
}

/*
* The following styles are auto-applied to elements with
* transition="modal" when their visibility is toggled
* by Vue.js.
*
* You can easily play with the modal transition by editing
* these styles.
*/

.modal-enter {
opacity: 0;
}

.modal-leave-active {
opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
-webkit-transform: scale(1.1);
transform: scale(1.1);
}
</style>
