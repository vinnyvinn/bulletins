<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <router-link to="/routes" class="btn btn-danger btn-xs">Back</router-link>
            <strong>Route: {{ route.source }} - {{ route.destination }}</strong>
          </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <h5><strong>From</strong></h5>
                    {{ route.source }}
                    <hr>

                    <h5><strong>To</strong></h5>
                    {{ route.destination }}
                    <hr>

                    <h5><strong>Distance</strong></h5>
                    {{ route.distance }}
                    <hr>
                </div>
                <div class="col-sm-6">
                    <h5><strong>Fuel Required</strong></h5>
                    {{ route.fuel_required }}
                    <hr>

                    <h5><strong>Allowance</strong></h5>
                    {{ route.allowance_amount }}
                    <hr>
                </div>

                <show-udfs module="Routes" :state="route"></show-udfs>
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
        fuel_required: '',
        allowance_amount: 0
      },
    }
  },
  created () {
    this.getRoute()
  },
  methods: {
    getRoute() {
      http.get('/api/route/' + this.$route.params.id).then(response => {
        this.route = response.route;

      });
    }
  }
}
</script>
<style lang="css">
</style>
