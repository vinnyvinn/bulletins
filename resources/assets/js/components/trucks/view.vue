<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Truck Details</strong>
          </div>

          <div class="panel-body">

            <div class="row">
              <div class="col-md-6">
                <strong>Plate Number:</strong><p>{{ truck.plate_number }}</p>
              </div>

              <div class="col-md-6">
                <strong>Make:</strong><p>{{ truck.make }}</p>
              </div>

              <div class="col-md-6">
                <strong>Model:</strong><p>{{ truck.model }}</p>
              </div>

              <div class="col-md-6">
                <strong>T/Weight:</strong><p>{{ Number(truck.max_load).toLocaleString() }} KGs</p>
              </div>
              <div class="col-md-6">
                <strong>Driver:</strong><p>{{ truck.driver ? truck.driver.name : 'No Driver' }}</p>
              </div>
              <div class="col-md-6">
                <strong>Trailer:</strong><p>{{ truck.trailer ? truck.trailer.trailer_number : 'No Trailer' }}</p>
              </div>
              <div class="col-md-6">
                <strong>Status:</strong><p>{{ truck.status }}</p>
              </div>
              <div class="col-md-6">
                <strong>Current Stage:</strong><p>{{ truck.location }}</p>
              </div>


            </div>
              <show-udfs module="Trucks" :state="truck"></show-udfs>
            <div class="col-md-12">
            <router-link to="/trucks" class="btn btn-danger pull-right">Back</router-link>
            </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
  data (){
      return {
          trailers: [],
          drivers: [],
          truck: {
              driver_id: '',
              trailer_id: '',
              plate_number: '',
              max_load: '',
              make: '',
              model: '',
              status: 'Active',
              location: 'Awaiting Allocation',
          }
      }
  },
  created () {
    this.getRoute()
  },
  methods: {
    getTruck () {
      http.get('/api/truck/' + this.$route.params.id).then(response => {
        this.route = response.route;
        console.log(response.route);
      });
    }
  }
}
</script>
<style lang="css">
</style>
