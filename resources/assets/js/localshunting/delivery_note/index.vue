<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
          Trucks Awaiting Loading
          <button v-if="$root.can('ls-create-delivery')" type="button" class="btn btn-success btn-xs pull-right" name="button" @click="offload">Offloading</button>
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
              <td>
                <button v-if="$root.can('ls-create-delivery')" type="button" @click="createDelivery(gatepass.vehicle.id)" name="button" class="btn btn-sm btn-success">Create Delivery Note</button>
              </td>
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
    this.$root.isLoading = true;
    http.get('/api/lsgatepass?contract=' + window.Laravel.contract_id).then((response) => {
      this.gatepasses = response.gatepasses;
      this.$root.isLoading = false;
    })
  },
  methods: {
    createDelivery(id) {
      this.$router.push('/ls/delivery/create/' + id);
    },
    offload () {
      this.$router.push('/ls/offloading');
    }
  }
}
</script>

<style lang="css">
</style>
