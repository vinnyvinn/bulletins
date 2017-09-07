<template lang="html">
  <div class="container">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            <router-link to="/employees" class="btn btn-danger btn-xs">Back</router-link>
            <strong>{{ driver.first_name }} {{ driver.last_name }}</strong>
          </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <h5><strong>First Name</strong></h5>
                    {{ driver.first_name }}
                    <hr>

                    <h5><strong>Last Name</strong></h5>
                    {{ driver.last_name }}
                    <hr>

                    <h5><strong>{{ driver.identification_type }}</strong></h5>
                    {{ driver.identification_number }}
                    <hr>
                </div>
                <div class="col-sm-6">
                    <h5><strong>Driving License</strong></h5>
                    {{ driver.dl_number }}
                    <hr>

                    <h5><strong>Mobile</strong></h5>
                    {{ driver.mobile_phone }}
                    <hr>
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
  data() {
    return {
      driver: {
          payroll_number: '',
          identification_number: '',
          identification_type: 'National ID',
          first_name: '',
          last_name: '',
          email: '',
          dl_number: '',
          mobile_phone: ''
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
