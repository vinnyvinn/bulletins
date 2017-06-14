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
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="fuel in fuels">
                    <td>JRNY-{{ fuel.journey_id }}</td>
                    <td>{{ fuel.date }}</td>
                    <td>{{ fuel.journey.driver.first_name }}</td>
                    <td>{{ fuel.journey.truck.plate_number }}</td>
                    <td>{{ fuel.journey.route.source }}</td>
                    <td>{{ fuel.journey.route.destination }}</td>
                    <td>{{ fuel.journey.route.distance }}</td>
                    <td>{{ fuel.current_fuel }}</td>
                    <td>{{ fuel.fuel_requested }}</td>
                    <td>{{ fuel.fuel_issued }}</td>
                    <td>{{ fuel.status }}</td>
                    <td>
                      <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-xs btn-success" @click="approveFuel(fuel.id)">Approve</button>
                      <button type="button" name="button" v-else class="btn btn-xs btn-success" @click="approveFuel(fuel.id)">Cancel Approval</button>
                      <span @click="edit(fuel)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                      <span @click="edit(fuel)" class="btn btn-xs btn-success"><i class="fa fa-print"></i></span>
                      <button data-toggle="popover" :data-item="fuel.id" class="btn btn-xs btn-danger btn-destroy">
                          <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Vehicle</th>
                    <th>Source</th>
                    <th>Destination</th>
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
        this.fuels = response.fuel;
        this.setupConfirm();
        prepareTable();
    });
  },

  data() {
      return {
          fuels: []
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

      approveFuel(id) {
        http.get('/api/approve/' + id).then(response => {
          if (response.status != 'success') {
              this.$root.isLoading = false;
              alert2(this.$root, [response.message], 'danger');
              return;
          }
          $('table').dataTable().fnDestroy();
          this.fuels = response.fuel;
          prepareTable();
          this.$root.isLoading = false;
          alert2(this.$root, [response.message], 'success');
          }).catch((error) => {
          this.$root.isLoading = false;
          alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
        });
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
              this.fuels = response.fuel;
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
