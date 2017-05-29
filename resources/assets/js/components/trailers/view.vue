<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Trailer Details</strong>
          </div>

          <div class="panel-body">

            <div class="row">
              <div class="col-md-6">
                <strong>Trailer Number:</strong><p>{{ trailer.trailer_number }}</p>
              </div>

              <div class="col-md-6">
                <strong>Make:</strong><p>{{ trailer.make }}</p>
              </div>

              <div class="col-md-6">
                <strong>Type:</strong><p>{{ trailer.type }}</p>
              </div>

               <div class="col-md-6">
                <strong>Attached To:</strong><p>{{ trailer.truck ? trailer.truck.plate_number : 'No Truck' }}</p>
              </div>

            </div>
              <show-udfs module="Trailers" :state="trailer"></show-udfs>

            <div class="col-md-12">
            <router-link to="/trailers" class="btn btn-danger pull-right">Back</router-link>
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
        trailer: {
            trailer_number: '',
            type: '',
            make: '',
        }
    }
  },
  created () {
    this.getTrailer();
  },
  methods: {
    getTrailer () {
      http.get('/api/trailer/' + this.$route.params.id).then(response => {
        this.route = response.route;
        console.log(response.route);
      });
    }
  }
}
</script>
<style lang="css">
</style>
