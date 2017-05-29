<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>{{ driver.name }}</strong>
          </div>

          <div class="panel-body">

            <div class="row">
              <div class="col-md-6">
                <strong>Name:</strong>
              </div>
              <div class="col-md-6">
                {{ driver.name }}
              </div>

              <div class="col-md-6">
                <strong>National Id:</strong>
              </div>
              <div class="col-md-6">
                {{ driver.national_id }}
              </div>

              <div class="col-md-6">
                <strong>Driving License:</strong>
              </div>
              <div class="col-md-6">
                {{ driver.dl_number }}
              </div>

              <div class="col-md-6">
                <strong>Mobile:</strong>
              </div>
              <div class="col-md-6">
                {{ driver.mobile }}
              </div>

            </div>
              <show-udfs module="Drivers" :state="driver"></show-udfs>

            </div>
          </div>
        </div>
    </div> 
</template>

<script>
export default {
  data (){
    return {
      driver: {
        name: '',
        national_id: '',
        dl_number: '',
        mobile: ''
      }
    }
  },
  created () {
    this.getDriver()
  },
  methods: {
    getDriver () {
      http.get('/api/driver/' + this.$route.params.id).then(response => {
        this.driver = response.driver;
      });
    }
  }
}
</script>

<style lang="css">
</style>
