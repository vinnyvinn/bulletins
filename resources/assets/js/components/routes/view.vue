<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Route Details</strong>
          </div>

          <div class="panel-body">

            <div class="row">
              <div class="col-md-6">
                <strong>From:</strong><p>{{ route.source }}</p>
              </div>

              <div class="col-md-6">
                <strong>To:</strong><p>{{ route.destination }}</p>
              </div>

              <div class="col-md-6">
                <strong>Distance:</strong><p>{{ route.distance }}</p>
              </div>

              <div class="col-md-6">
                <strong>Fuel Required:</strong><p>{{ Number(route.fuel_required).toLocaleString() }} Ltrs</p>
              </div>

            </div>
              <show-udfs module="Routes" :state="route"></show-udfs>
            <div class="col-md-12">
            <router-link to="/routes" class="btn btn-danger pull-right">Back</router-link>
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
        route: {
            source: '',
            destination: '',
            distance: '',
            fuel_required: 1,
            allowance_amount: 0
        }
    }
  },
  created () {
    this.getRoute()
  },
  methods: {
    getRoute () {
      http.get('/api/route/' + this.$route.params.id).then(response => {
        this.route = response.route;
        console.log(response.route);
      });
    }
  }
}
</script>
<style lang="css">
</style>
