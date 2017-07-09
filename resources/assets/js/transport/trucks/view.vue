<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <router-link to="/trucks" class="btn btn-danger btn-xs">< Trucks</router-link>
            <router-link to="/trucksreports" class="btn btn-danger btn-xs">Reports</router-link>
          </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <h5><strong>Driver</strong></h5>
                    {{ truck.driver.first_name }} {{ truck.driver.last_name }}
                    <hr>

                    <h5><strong>Trailer</strong></h5>
                    {{ truck.type }}
                    <hr>

                    <h5><strong>Plate Number</strong></h5>
                    {{ truck.make }}
                    <hr>
                </div>

                <div class="col-sm-6">
                    <h5><strong>Max Load</strong></h5>
                    {{ truck.max_load }}
                    <hr>

                    <h5><strong>Make</strong></h5>
                    {{ truck.make }}
                    <hr>

                    <h5><strong>Model</strong></h5>
                    {{ truck.model }}
                    <hr>
                </div>

                <div class="col-sm-6">
                    <h5><strong>Status</strong></h5>
                    {{ truck.status }}
                    <hr>

                    <h5><strong>Location</strong></h5>
                    {{ truck.location }}
                    <hr>
                </div>
                <show-udfs module="Trucks" :state="truck"></show-udfs>
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
      truck: {
        driver_id: '',
        trailer_id: '',
        plate_number: '',
        max_load: '',
        make: '',
        model: '',
        status: '',
        location: ''
      },
    }
  },
  created () {
    this.getTruck()
  },
  methods: {
    getTruck() {
      http.get('/api/truck/' + this.$route.params.id).then(response => {
        this.truck = response.truck;
      });
    }
  }
}
</script>

<style lang="css">
</style>
