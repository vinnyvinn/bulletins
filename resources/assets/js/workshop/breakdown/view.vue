<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Breakdown Incident #BRK-{{ breakdown.id }}</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form">

                    <div class="row">
                        <div class="col-sm-4">
                            <h4>
                                <strong>Breakdown Incident Number:</strong>
                            </h4>
                            <h5>BRK-{{ breakdown.id }}</h5>
                        </div>

                        <div class="col-sm-4">
                            <h4>
                                <strong>Logged On:</strong>
                            </h4>
                            <h5>{{ formatDate(breakdown.created_at) }} {{ formatTime(breakdown.created_at) }}</h5>
                        </div>
                    </div>
                    <hr>


                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                      <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="headingOne">
                          <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                              Breakdown Login
                            </a>
                          </h4>
                        </div>

                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                          <div class="panel-body">

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group input-group-sm">
                                        <label for="vehicle_id">Vehicle/Chassis Number</label>
                                        <select disabled required v-model="breakdown.vehicle_id" name="vehicle_id" id="vehicle_id" class="form-control select2">
                                            <option v-for="vehicle in vehicles" :value="vehicle.id" :key="vehicle.id">{{ vehicle.plate_number }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group input-group-sm">
                                        <label for="breakdown_area_id">Area</label>
                                        <select disabled required v-model="breakdown.breakdown_area_id" name="breakdown_area_id" id="breakdown_area_id" class="form-control select2">
                                            <option v-for="area in areas" :value="area.id" :key="area.id">{{ area.name }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group input-group-sm">
                                        <label for="driver_id">Driver</label>
                                        <select disabled required v-model="breakdown.driver_id" name="driver_id" id="driver_id" class="form-control select2">
                                            <option v-for="driver in drivers" :value="driver.id" :key="driver.id">{{ driver.first_name }} {{ driver.last_name }}</option>
                                        </select>
                                    </div>

                                     <div class="form-group input-group-sm">
                                        <label for="location">Location</label>
                                        <input disabled required type="text" v-model="breakdown.location" name="location" id="location" class="form-control">
                                    </div>
                                </div>


                                <div class="col-sm-4">
                                    <div class="form-group input-group-sm">
                                        <label for="description">Description</label>
                                        <textarea disabled required v-model="breakdown.description" name="description" id="description" cols="20" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                              <div class="col-sm-12">
                                  <h5><strong>INCEDENT DETAILS</strong></h5>
                                  <hr>
                              </div>
                                <div class="col-sm-12">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(item, index) in breakdown.incident_details">
                                            <td>{{ index + 1}}</td>
                                            <td>{{ item }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                          </div>
                        </div>
                      </div>

                      <div class="panel panel-default" v-if="breakdown.status == 'Approved' || breakdown.status == 'Closed'">
                        <div class="panel-heading" role="tab" id="headingTwo">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                              Breakdown Recovered
                            </a>
                          </h4>
                        </div>

                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">

                            <div class="row">
                              <div class="col-sm-12">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Problem Area</th>
                                        <th>Problem Cause</th>
                                        <th>Action</th>
                                        <th>Remarks</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in breakdown.break_down_recovered">
                                        <td>{{ index + 1}}</td>
                                        <td>{{ item.problem_area }}</td>
                                        <td>{{ item.problem_cause }}</td>
                                        <td>{{ item.problem_action }}</td>
                                        <td>{{ item.problem_remarks }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                              </div>
                            </div>


                            <div class="row">
                              <div class="col-sm-12">
                                <div class="form-group">
                                  <label for="narration">Narration</label>
                                  <textarea disabled name="narration" rows="8" id="narration" v-model="breakdown.narration" class="form-control"></textarea>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>

                      <div class="panel panel-default" v-if="breakdown.status == 'Approved' || breakdown.status == 'Closed'">
                        <div class="panel-heading" role="tab" id="headingThree">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                              OIMS
                            </a>
                          </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                          <div class="panel-body">

                          </div>
                        </div>
                      </div>

                      <div class="panel panel-default" v-if="breakdown.status == 'Approved' || breakdown.status == 'Closed'">
                        <div class="panel-heading" role="tab" id="headingFour">
                          <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                              Job Cards
                            </a>

                            <a href="#" v-if="breakdown.status == 'Approved'" @click.prevent="jobCard()" class="pull-right btn btn-success btn-xs">New Job Card</a>
                          </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                          <div class="panel-body">
                              <table class="table datatable nowrap">
                                  <thead>
                                  <tr>
                                      <th>No.</th>
                                      <th>Card #</th>
                                      <th>Job Type</th>
                                      <th>Description</th>
                                      <th>Created On</th>
                                      <th>Expected Completion</th>
                                      <th></th>
                                  </tr>
                                  </thead>

                                  <tbody>
                                  <tr v-for="(card, index) in breakdown.job_cards">
                                      <td>{{ index + 1 }}</td>
                                      <td><a @click.prevent="viewCard(card.id)">JC-{{ card.id }}</a></td>
                                      <td>{{ card.type.name }}</td>
                                      <td>{{ card.job_description }}</td>
                                      <td>{{ formatDate(card.created_at) }}</td>
                                      <td>{{ formatDate(card.expected_completion) }}</td>
                                      <td class="text-center">
                                          <span @click="editCard(card.id)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                          <button data-toggle="popover" :data-item="card.id" class="btn btn-xs btn-danger btn-destroy"><i class="fa fa-trash"></i></button>
                                      </td>
                                  </tr>
                                  </tbody>

                                  <tfoot>
                                  <tr>
                                      <th>No.</th>
                                      <th>Card #</th>
                                      <th>Job Type</th>
                                      <th>Description</th>
                                      <th>Created On</th>
                                      <th>Expected Completion</th>
                                      <th></th>
                                  </tr>
                                  </tfoot>
                              </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <hr>


                    <div class="form-group">
                        <button v-if="breakdown.status == 'Approved'" class="btn btn-warning" @click.prevent="closeCard">Close Incident</button>
                        <span v-if="breakdown.status == 'Pending'">
                            <button class="btn btn-success" @click.prevent="approve()">Approve Incident</button>
                            <button class="btn btn-danger" @click.prevent="disapprove()">Disapprove Incident</button>
                        </span>
                        <router-link to="/wsh/breakdown" class="btn btn-danger">Back</router-link>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          vehicles: [],
          drivers: [],
          areas: [],
          incedent: '',
          breakdown: {
            vehicle_id: null,
            breakdown_area_id: null,
            location: null,
            description: null,
            incident_details: [],
            vehicle_sent: null,
            attended_by: null,
            break_down_recovered: null,
            narration: null,
            breakdown_approved: null,
            breakdown_approved_by: null,
            oims: null,
            status: null,
            vehicle_id: null,
            jobCards: [],
          },

          editing: false,
          status: null,
        };
      },

      created() {
        this.$root.isLoading = true;
        http.get("/api/breakdown/" + this.$route.params.id + '?full=t')
          .then(response => {
            this.vehicles = response.vehicles;
            this.areas = response.areas;
            this.drivers = response.drivers;
            this.breakdown = response.breakdown;
            this.$root.isLoading = false;

            setTimeout(() => prepareTable(), 500);
          });
      },


      methods: {
          formatDate(date) {
              date = new Date(date);
              let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

              let month = months[date.getMonth()];
              let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

              return day + ' ' + month + ' ' + date.getFullYear();
          },

          formatTime(time) {
            time = new Date(time);
            return time.getHours() + ':' + time.getMinutes();
          },


          approve() {
              this.$root.isLoading = true;
              http.post('/api/breakdown/' + this.$route.params.id + '/approve', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/breakdown' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          jobCard() {
              this.$root.isLoading = true;
              http.post('/api/breakdown/' + this.$route.params.id + '/job-card', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/job-card/' + response.jobCard + '/edit' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          disapprove() {
              this.$root.isLoading = true;
              http.post('/api/breakdown/' + this.$route.params.id + '/disapprove', {}).then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  this.$root.isLoading = false;
                  window._router.push({ path: '/wsh/breakdown' });
              }).catch((error) => {
                  this.$root.isLoading = false;
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },

          closeCard() {
              let request = null;
              request = http.post('/api/breakdown/' + this.$route.params.id + '/close', {});

              request.then((response) => {
                  alert2(this.$root, [response.message], 'success');
                  window._router.push({ path: '/wsh/breakdown' });
              }).catch((error) => {
                  alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
              });
          },
      }
    }
</script>
