<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <router-link to="/trailers" class="btn btn-danger btn-xs">Back</router-link>
          </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <h5><strong>Trailer Number</strong></h5>
                    {{ trailer.trailer_number }}
                    <hr>

                    <h5><strong>Type</strong></h5>
                    {{ trailer.type }}
                    <hr>

                    <h5><strong>Make</strong></h5>
                    {{ trailer.make }}
                    <hr>
                </div>
                <show-udfs module="Trailers" :state="trailer"></show-udfs>
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
        make: ''
      },
    }
  },
  created () {
    this.getTrailer()
  },
  methods: {
    getTrailer() {
      http.get('/api/trailer/' + this.$route.params.id).then(response => {
        this.trailer = response.trailer;
      });
    }
  }
}
</script>
<style lang="css">
</style>
