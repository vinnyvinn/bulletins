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
                    <h5><strong>Type</strong></h5>
                    {{ truck.type }}
                    <hr>

                    <h5><strong>Trailer</strong></h5>
                    {{ truck.trailer ? truck.trailer.plate_number : '' }}
                    <hr>

                    <h5><strong>Vehicle Number</strong></h5>
                    {{ truck.plate_number }}
                    <hr>
                </div>

                <div class="col-sm-6">
                    <h5><strong>Max Load</strong></h5>
                    {{ truck.max_load }}
                    <hr>

                    <h5><strong>Make</strong></h5>
                    {{ truck.make ? truck.make.name : '' }}
                    <hr>

                    <h5><strong>Model</strong></h5>
                    {{ truck.model ? truck.model.name : '' }}
                    <hr>
                </div>

                <div class="col-sm-6">
                    <h5><strong>Status</strong></h5>
                    {{ truck.status }}
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
        let truck = response.truck;
        truck.driver = truck.driver ? truck.driver : {};
        truck.trailer = truck.trailer ? truck.trailer : {};

        this.truck = truck;
      });
    }
  }
}
</script>

<style lang="css">
</style>
