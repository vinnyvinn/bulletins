<template lang="html">
  <div class="container">
    <div class="row hidden-print">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Processed Gate Passes</strong>

            <router-link to="/wsh/gatepass" class="btn btn-info btn-xs pull-right">
                <i class="fa fa-plus"></i> Pending Approval
            </router-link>

            <router-link to="/wsh/gatepass/disapproved" class="btn btn-danger btn-xs pull-right">
                <i class="fa fa-plus"></i> Disapproved
            </router-link>

            <router-link to="/wsh/gatepass/create" class="btn btn-success btn-xs pull-right">
                <i class="fa fa-plus"></i> New Gatepass
            </router-link>
          </div>
          <div class="panel-body">
            <div class="table-responsive">
              <table class="table nowrap">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Gatepass No.</th>
                    <th>Print</th>
                    <th>External Service No.</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Supplier</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="gatepass in gatepasses">
                    <td>{{ gatepass.id }}</td>
                    <td>
                      <router-link :to="'/wsh/gatepass/' + gatepass.id">EXPASS-{{ gatepass.id }}</router-link>
                    </td>
                    <td>
                      <button class="btn btn-success btn-xs" @click.prevent="printPass(gatepass.id)">PRINT</button>
                    </td>
                    <td>
                      <router-link :to="'/wsh/external/' + gatepass.external_service_id">ES-{{ gatepass.external_service_id }}</router-link>
                    </td>
                    <td>{{ gatepass.type }}</td>
                    <td>{{ date2(gatepass.created_at) }}</td>
                    <td>{{ gatepass.supplier_name }}</td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Gatepass No.</th>
                    <th>External Service No.</th>
                    <th>Print</th>
                    <th>Vehicle</th>
                    <th>Date</th>
                    <th>Supplier</th>
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
      components:{

      },
    created() {
      http.get('/api/wsh-gatepass/?status=Approved').then(response => {
        this.gatepasses = response.gatepasses;
        this.setupConfirm();
        prepareTable(false, [], {
          "columnDefs": [
            {
              "targets": [0],
              "visible": false,
              "searchable": false
            },
          ],
          "orderFixed": [0, "desc"],
        });
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
        http.get('/api/wsh-gatepass/' + id + '/print').then(response => {
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
        window._router.push({ path: '/wsh/gatepass/' + gatepass.id + '/edit' })
      },
      destroy(id) {
        this.$root.isLoading = true;

        http.destroy('/api/wsh-gatepass/' + id + '/?s=' + window.Laravel.station_id).then(response => {
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
    }
  }
</script>
