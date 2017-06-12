<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Fuel Allocation</strong>
            <router-link to="/fuel/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table no-wrap">
                <thead>
                  <tr>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Vehicle</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Vehicle</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Vehicle</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </tfoot>                
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  created() {
    http.get('/api/fuel').then(response => {
        this.fuel = response.fuels;
        this.setupConfirm();
        prepareTable();
    });
  },

  data() {
      return {
          fuel: []
      };
  },

  methods: {
      setupConfirm() {
          $('.btn-destroy').off();
          confirm2('.btn-destroy', (element) => {
              this.destroy(element.dataset.item);
          });
      },
      date2(value) {
          return window._date2(value);
      },

      edit(fuel) {
          window._router.push({path: '/fuel/' + fuel.id + '/edit'})
      },

      destroy(id) {
          this.$root.isLoading = true;

          http.destroy('api/fuel/' + id).then(response => {
              if (response.status != 'success') {
                  this.$root.isLoading = false;
                  alert2(this.$root, [response.message], 'danger');
                  return;
              }
              $('table').dataTable().fnDestroy();
              this.fuel = response.fuel;
              prepareTable();
              this.$root.isLoading = false;
              alert2(this.$root, [response.message], 'success');
          }).catch((error) => {
              this.$root.isLoading = false;
              alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
          });
      }
  }
}
</script>

<style lang="css">
</style>
