<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
          Trucks Awaiting Loading
      </div>
      <div class="panel-body">
        <table class="table no-wrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Plate #</th>
              <th>Gate Pass #</th>
              <th>Driver</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(gatepass, index) in gatepasses">
              <td>{{ index + 1 }}</td>
              <td>{{ gatepass.vehicle.plate_number }}</td>
              <td>GP - {{ gatepass.id}}</td>
              <td v-if="gatepass.vehicle.driver">{{ gatepass.vehicle.driver.first_name }} {{ gatepass.vehicle.driver.last_name }}</td>
              <td v-if="!gatepass.vehicle.driver"> -- </td>
              <td><button type="button" @click="createDelivery(gatepass.vehicle.id)" name="button" class="btn btn-sm btn-success">Create Delivery Note</button></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th>#</th>
              <th>Plate #</th>
              <th>Gate Pass #</th>
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
      gatepasses: []
    }
  },
  created () {
    http.get('/api/lsgatepass').then((response) => {
      this.gatepasses = response.gatepasses;
    })
  },
  methods: {
    createDelivery(id) {
      this.$router.push('/ls/delivery/create/' + id);
    }
  }
}
</script>

<style lang="css">
</style>
