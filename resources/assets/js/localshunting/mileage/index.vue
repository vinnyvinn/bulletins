<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
          Awaiting Mileage
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

  </div>
</template>

<script>
export default {
  data () {
    return {
      vehicles: []
    }
  },
  created () {
    http.get('/api/lsmileage').then((response) => {
      this.vehicles = response.vehicles;
    })
  },
  methods: {
    createMileage(vehicle_id, contract_id) {
      this.$router.push('/ls/mileage/create/' + vehicle_id + '/' + contract_id);
    }
  }
}
</script>

<style lang="css">
</style>
