<template lang="html">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Fuel Allocation</strong>
            <!--<router-link v-if="$root.can('create-fuel')" to="/fuel/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>-->
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table nowrap">
                <thead>
                  <tr>
                    <th>Status</th>
                    <th>Vehicle</th>

                    <th>Fuel No.</th>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Source - Destination</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Issued By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="fuel in fuels">
                    <td v-if="fuel.status == 'Awaiting Approval' && $root.can('approve-fuel')">
                      <button type="button" name="button" v-if="fuel.status == 'Awaiting Approval'" class="btn btn-xs btn-success" @click="approveFuel(fuel.id)">Approve</button>
                    </td>
                    <td v-else>{{ fuel.status }}</td>
                    <td><span v-if="fuel.journey">{{ fuel.journey.truck.plate_number }}</span></td>
                    <td>
                        <router-link v-if="$root.can('view-fuel')" :to="'/fuel/' + fuel.id">FUEL-{{ fuel.id }}</router-link>
                        <span v-else>FUEL-{{ fuel.id }}</span>
                    </td>
                    <td>JRNY-{{ fuel.journey_id }}</td>
                    <td>{{ fuel.date }}</td>
                    <td><span v-if="fuel.journey">{{ fuel.journey.driver.first_name }} {{ fuel.journey.driver.last_name }}</span></td>
                    <td><span v-if="fuel.journey">{{ fuel.journey.route.source }} - {{ fuel.journey.route.destination }}</span></td>
                    <td><span v-if="fuel.journey">{{ fuel.journey.route.distance }}</span></td>
                    <td>{{ fuel.current_fuel }}</td>
                    <td>{{ fuel.fuel_requested }}</td>
                    <td>{{ fuel.fuel_issued }}</td>
                    <td v-if="fuel.user">{{ fuel.user.first_name }} {{ fuel.user.last_name }}</td>
                    <td v-if="!fuel.user">---</td>
                    <td>
                      <span @click="edit(fuel)" v-if="$root.can('edit-fuel')" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                      <button v-if="$root.can('delete-fuel')" data-toggle="popover" :data-item="fuel.id" class="btn btn-xs btn-danger btn-destroy">
                          <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Status</th>
                    <th>Vehicle</th>

                    <th>Fuel No.</th>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Source - Destination</th>
                    <th>Journey Distance</th>
                    <th>Current Fuel</th>
                    <th>Fuel Requested</th>
                    <th>Fuel Issued</th>
                    <th>Issued By</th>
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
    this.$root.isLoading = true;
    http.get('/api/fuel/?s=' + window.Laravel.station_id).then( (response) => {
        this.fuels = response.fuel;
        this.setupConfirm();
        prepareTable();
        this.$root.isLoading = false;
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
      destroy(id) {
          this.$root.isLoading = true;

          http.destroy('/api/fuel/' + id + '/?s=' + window.Laravel.station_id).then(response => {
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
      approveFuel(id) {
        http.get('/api/approve/' + id).then(response => {
          if (response.status != 'success') {
              this.$root.isLoading = false;
              alert2(this.$root, [response.message], 'danger');
              return;
          }

          http.get('/api/fuel/?s=' + window.Laravel.station_id).then(response => {
              this.fuels = response.fuel;
              this.setupConfirm();
          });

          this.$root.isLoading = false;
          alert2(this.$root, [response.message], 'success');
          }).catch((error) => {
          this.$root.isLoading = false;
          alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
        });
      },
  }
}
</script>

<style lang="css">
</style>
