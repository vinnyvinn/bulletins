<template lang="html">
  <div class="container">
    <div class="row hidden-print">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Gate Passes</strong>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table nowrap">
                <thead>
                  <tr>
                    <th>Print</th>
                    <th>Gatepass No.</th>
                    <th>Vehicle</th>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Source - Destination</th>
                    <th>Journey Distance</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="gatepass in gatepasses">
                    <td><button class="btn btn-success btn-xs" @click.prevent="printPass(gatepass.id)">PRINT</button></td>
                    <td v-if="$root.can('view-gatepass')"><router-link :to="'/gatepass/' + gatepass.id">PASS-{{ gatepass.id }}</router-link></td>
                    <td v-else>PASS-{{ gatepass.id }}</td>
                    <td>{{ gatepass.journey.truck.plate_number }}</td>
                    <td>JRNY-{{ gatepass.journey_id }}</td>
                    <td>{{ gatepass.gatepass_date }}</td>
                    <td>{{ gatepass.journey.driver.first_name }} {{ gatepass.journey.driver.last_name }}</td>
                    <td>{{ gatepass.journey.route.source }} - {{ gatepass.journey.route.destination }}</td>
                    <td>{{ gatepass.journey.route.distance }}</td>
                    <td>
                      <span @click="edit(gatepass)" v-if="$root.can('edit-gatepass')" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                      <button v-if="$root.can('delete-gatepass')" data-toggle="popover" :data-item="gatepass.id" class="btn btn-xs btn-danger btn-destroy">
                          <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Print</th>
                    <th>Gatepass No.</th>
                    <th>Vehicle</th>
                    <th>Journey</th>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Source - Destination</th>
                    <th>Journey Distance</th>
                    <th>Action</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="visible-print-block" id="printable" v-html="printout"></div>
  </div>
</template>

<script>
export default {
  created() {
    http.get('/api/gatepass/?s=' + window.Laravel.station_id).then(response => {
        this.gatepasses = response.gatepasses;
        this.setupConfirm();
        prepareTable();
    });
  },

  data() {
      return {
          gatepasses: [],
          printout: '',
      };
  },

  methods: {
        printPass(id) {
          this.$root.isLoading = true;
          http.get('/api/gatepass/print/' + id).then(response => {
              this.printout = response.printout;
              this.$root.isLoading = false;
              setTimeout(() => {
                  if (response.shouldPrint) {
                      window.print();
                  }
              }, 500);
          }).catch(() => this.$root.isLoading = false);
      },
      setupConfirm() {
          $('.btn-destroy').off();
          confirm2('.btn-destroy', (element) => {
              this.destroy(element.dataset.item);
          });
      },
      date2(value) {
          return window._date2(value);
      },

      edit(gatepass) {
          window._router.push({path: '/gatepass/' + gatepass.id + '/edit'})
      },
      destroy(id) {
          this.$root.isLoading = true;

          http.destroy('/api/gatepass/' + id + '/?s=' + window.Laravel.station_id).then(response => {
              if (response.status != 'success') {
                  this.$root.isLoading = false;
                  alert2(this.$root, [response.message], 'danger');
                  return;
              }
              $('table').dataTable().fnDestroy();
              this.gatepasses = response.gatepasses;
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
