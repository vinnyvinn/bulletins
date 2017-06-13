<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="journey_id">Journey</label>
                        <select class="" name="journey_id" v-model="fuel.journey_id" class="form-control input-sm select2" required>
                          <option v-for="journey in journeys" :value="journey.id">JRNY-{{ journey.id }}</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" v-model="fuel.date" class="form-control input-sm datepicker" id="date" name="date" required>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="current_fuel">Current Fuel (Litres)</label>
                        <input type="text" name="current_fuel" class="form-control input-sm" v-model="fuel.current_fuel">
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group">
                        <label for="fuel_issued">Fuel Issued (Litres)</label>
                        <input type="text" name="fuel_issued" class="form-control input-sm" v-model="fuel.fuel_issued">
                      </div>
                    </div>

                </div>
                <div class="form-group">
                    <button class="btn btn-success">Save</button>
                    <router-link to="/journey" class="btn btn-danger">Back</router-link>
                </div>
            </form>

        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/fuel/create').then((response) => {
                this.journeys = response.journeys;
            });
        },

        mounted() {
            this.setupUI();
            $('input[type="number"]').on('focus', function () {
                this.select();
            });
        },

        data() {
            return {
                journeys: [],
                fuel: {
                    journey_id: '',
                    date: '',
                    current_fuel: '',
                    fuel_issued: '',
                    status: ''
                }
            };
        },

        methods: {
          setupUI() {
              $('.datepicker').datepicker({
                  autoclose: true,
                  format: 'dd/mm/yyyy',
                  todayHighlight: true,
              });

              $('#date').datepicker().on('changeDate', (e) => {
                  this.fuel.date = e.date.toLocaleDateString('en-GB');
              });
          },

            store() {
                this.$root.isLoading = true;
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/fuel/' + this.$route.params.id, this.fuel, true);
                } else {
                    request = http.post('/api/fuel', this.fuel, true);
                }

                request.then((response) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/fuel' });
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            },
        }
    }
</script>
