<template lang="html">
  <div class="container">
    <div class="panel panel-default" v-if="!showEmployees">
      <div class="panel-heading">
          Awaiting Mileage
          <button type="button" name="button" class="btn btn-success btn-sm pull-right" @click="employeeMileage">Supervisor/Casuals Mileage</button>
      </div>
      <div class="panel-body">
        <table class="table no-wrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Plate #</th>
              <th>Driver</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(vehicle, index) in vehicles">
              <td>{{ index + 1 }}</td>
              <td>{{ vehicle.plate_number }}</td>
              <td v-if="vehicle.driver">{{ vehicle.driver.first_name }} {{ vehicle.driver.last_name }}</td>
              <td v-if="!vehicle.driver"> -- </td>
              <td><button type="button" @click="createMileage(vehicle.id, vehicle.contract_id)" name="button" class="btn btn-sm btn-success">Allocate Mileage</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Plate #</th>
              <th>Driver</th>
              <th>Action</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <div class="panel panel-default" v-if="showEmployees">
      <div class="panel-heading">
        Employees Mileage
        <button type="button" name="button" class="btn btn-success btn-sm pull-right" @click="employeeMileage">Drivers Mileage</button>
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
            <tr v-for="(employee, index) in employees" v-if="employee.category == 'supervisor'">
              <td>{{ index + 1 }}</td>
              <td>{{ employee.first_name }} {{ employee.last_name}}</td>
              <td><button type="button" @click="createEmployeeMileage(employee)" name="button" class="btn btn-sm btn-success">Allocate Mileage</button></td>
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
            <tr v-for="(employee, index) in employees" v-if="employee.category == 'casual'">
              <td>{{ index + 1 }}</td>
              <td>{{ employee.first_name }} {{ employee.last_name}}</td>
              <td><button type="button" @click="createEmployeeMileage(employee)" name="button" class="btn btn-sm btn-success">Allocate Mileage</button></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data () {
    return {
      showEmployees: false,
      vehicles: [],
      employees: [],
    }
  },
  created () {
    this.$root.isLoading = true;
    http.get('/api/ls_mileage_employees/' + this.$route.params.contract).then((response) => {
      this.employees = response.employees;
      this.vehicles = response.vehicles;
      this.$root.isLoading = false;
    })
  },
  methods: {
    createMileage(vehicle_id, contract_id) {
      this.$router.push('/ls/mileage/create/' + vehicle_id + '/' + contract_id);
    },

    employeeMileage() {
      this.showEmployees = !this.showEmployees;
    },

    createEmployeeMileage (employee) {
      this.$router.push('/ls/mileage/employee/' + employee.id + '/' + employee.contract_id)
    }
  }
}
</script>

<style lang="css">
</style>
