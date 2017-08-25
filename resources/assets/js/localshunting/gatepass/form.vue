<template>
    <div class="container">
        <div class="panel panel-default">
          <div class="panel-heading">
            Gatepass Inwards
          </div>
          <div class="panel-body">
              <div class="row">
                <form v-if="$root.can('ls-create-gatepass')" action="#" role="form" @submit.prevent="store">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label for="truck">Select Vehicle</label>
                      <select class="select2 col-sm-12" name="truck" v-model="gatepass.vehicle_id" id="truck" required>
                        <option :value="vehicle.id" v-for="vehicle in vehicles">{{ vehicle.plate_number }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <button type="submit" name="button" class="btn btn-sm btn-success">Check In</button>
                  </div>
                </form>
              </div>


            <table class="table no-wrap">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Vehicle</th>
                  <th>Created By</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="gatepass in gatepasses">
                  <td>GP - {{ gatepass.id }}</td>
                  <td>{{ gatepass.vehicle.plate_number }} </td>
                  <td>{{ gatepass.user.first_name }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Vehicle</th>
                  <th>Created By</th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
          return {
            gatepass: {
              vehicle_id: ''
            },
            vehicles: [],
            gatepasses: []
          }
      },
      created() {
        this.$root.isLoading = true;
        http.get('/api/lsgatepass/create').then((response) => {
          this.vehicles = response.vehicles;
          this.gatepasses = response.gatepasses;

          this.$root.isLoading = false;
        });
      },

      mounted () {
        $(document).ready(() => {
          $('#truck').select2().on('change', e => {
              this.gatepass.vehicle_id = e.target.value;
          });
        });

      },
      methods: {
        updateFields () {
          $('#truck').select2('destroy');
          $('#truck').select2().on('change', e => this.gatepass.vehicle_id = e.target.value);
        },

        store() {
          http.post('/api/lsgatepass', this.gatepass).then((response) => {
            this.gatepass.vehicle_id = null;
            this.gatepasses = response.gatepasses;
            this.vehicles = response.vehicles;
            alert2(this.$root, [response.message], 'success');
          });
        },
      }

    }
</script>
