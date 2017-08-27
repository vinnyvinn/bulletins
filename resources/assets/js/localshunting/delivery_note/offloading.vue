<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
          Trucks Awaiting Off-loading
          <button v-if="$root.can('ls-create-delivery')" type="button" class="btn btn-success btn-xs pull-right" name="button" @click="load">Loading</button>
      </div>
      <div class="panel-body">
        <table class="table no-wrap">
          <thead>
            <tr>
              <th>#</th>
              <th>Delivery #</th>
              <th>Vehicle</th>
              <th>Driver</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(delivery, index) in deliveries">
              <td>{{ index + 1 }}</td>
              <td>DN - {{ delivery.id }}</td>
              <td>{{ delivery.vehicle.plate_number }}</td>
              <td v-if="delivery.vehicle.driver">{{ delivery.vehicle.driver.first_name }} {{ delivery.vehicle.driver.last_name }}</td>
              <td v-if="!delivery.vehicle"> -- </td>
              <td><button v-if="$root.can('ls-create-delivery')" type="button" @click="unload(delivery.id)" name="button" class="btn btn-sm btn-success">Offload</button></td>
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
      deliveries: []
    }
  },
  created () {
    this.$root.isLoading = true;
    http.get('/api/lsdelivery').then((response) => {
      this.deliveries = response.deliveries;
      this.$root.isLoading = false;
    })
  },
  methods: {
    createDelivery(id) {
      this.$router.push('/ls/delivery/create/' + id);
    },

    unload(delivery) {
        window._router.push({path: '/ls/delivery/' + delivery + '/unload'})
    },


    load() {
      this.$router.push('/ls/delivery');
    }
  }
}
</script>

<style lang="css">
</style>
