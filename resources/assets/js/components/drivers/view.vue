<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
            Driver
          </div>
          <div class="panel-body">
            {{ driver }}
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
      http.get('/api/driver/show' + this.$route.params.id).then(response => {
        this.driver = response;
      })
    }
  }
}
</script>

<style lang="css">
</style>
