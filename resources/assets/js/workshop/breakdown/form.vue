<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Breakdown</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">
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
                                      <select :disabled="editing" required v-model="breakdown.vehicle_id" name="vehicle_id" id="vehicle_id" class="form-control select2">
                                          <option v-for="vehicle in vehicles" :value="vehicle.id" :key="vehicle.id">{{ vehicle.plate_number }}</option>
                                      </select>
                                  </div>

                                  <div class="form-group input-group-sm">
                                      <label for="breakdown_area_id">Area</label>
                                      <select :disabled="breakdown.status != 'Pending' && breakdown.status != null" required v-model="breakdown.breakdown_area_id" name="breakdown_area_id" id="breakdown_area_id" class="form-control select2">
                                          <option v-for="area in areas" :value="area.id" :key="area.id">{{ area.name }}</option>
                                      </select>
                                  </div>
                              </div>

                              <div class="col-sm-4">
                                  <div class="form-group input-group-sm">
                                      <label for="driver_id">Driver</label>
                                      <select :disabled="breakdown.status != 'Pending' && breakdown.status != null" required v-model="breakdown.driver_id" name="driver_id" id="driver_id" class="form-control select2">
                                          <option v-for="driver in drivers" :value="driver.id" :key="driver.id">{{ driver.first_name }} {{ driver.last_name }}</option>
                                      </select>
                                  </div>

                                   <div class="form-group input-group-sm">
                                      <label for="location">Location</label>
                                      <input :disabled="status != 'Pending' && breakdown.status != null" required type="text" v-model="breakdown.location" name="location" id="location" class="form-control">
                                  </div>
                              </div>


                              <div class="col-sm-4">
                                  <div class="form-group input-group-sm">
                                      <label for="description">Description</label>
                                      <textarea :disabled="status != 'Pending' && breakdown.status != null" required v-model="breakdown.description" name="description" id="description" cols="20" rows="5" class="form-control"></textarea>
                                  </div>
                              </div>
                          </div>

                          <div class="row" v-if="breakdown.status == 'Pending' || breakdown.status == null">
                              <div class="col-sm-10">
                                <div class="form-group">
                                    <label for="incedent">Incident Details</label>
                                    <textarea v-model="incedent" name="incedent" rows="2" cols="80" class="form-control"></textarea>
                                </div>
                              </div>
                              <div class="col-sm-2">
                                <h5>&nbsp;</h5>
                                <button @click.prevent="addIncedent()" type="button" name="button" class="btn btn-block btn-primary">ADD</button>
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
                                          <th v-if="breakdown.status == 'Pending' || breakdown.status == null">Remove</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      <tr v-for="(item, index) in breakdown.incident_details">
                                          <td>{{ index + 1}}</td>
                                          <td>{{ item }}</td>
                                          <td v-if="breakdown.status == 'Pending' || breakdown.status == null">
                                              <button @click.prevent="removeIncedent(item)" type="button" name="button" class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                              </button>
                                          </td>
                                      </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>

                        </div>
                      </div>
                    </div>



                    <div class="panel panel-default" v-if="breakdown.status == 'Approved'">
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
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label for="problem_area">Problem Area</label>
                                <input type="text" name="problem_area" v-model="problem_area" class="form-control">
                              </div>

                              <div class="form-group">
                                <label for="problem_cause">Problem Cause</label>
                                <input type="text" name="problem_area" v-model="problem_cause" class="form-control">
                              </div>

                              <div class="form-group">
                                <label for="problem_action">Action to be taken</label>
                                <input type="text" name="problem_area" v-model="problem_action" class="form-control">
                              </div>
                            </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                                <label for="problem_remarks">Remarks</label>
                                <textarea rows="6" type="text" name="problem_remarks" v-model="problem_remarks" class="form-control"></textarea>
                              </div>

                              <div class="form-group">
                                <button type="button" class="btn btn-primary btn-block" @click.prevent="addProblem">ADD PROBLEM</button>
                              </div>
                            </div>

                          </div>

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
                                      <th v-if="breakdown.status == 'Approved'">Remove</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  <tr v-for="(item, index) in breakdown.break_down_recovered">
                                      <td>{{ index + 1}}</td>
                                      <td>{{ item.problem_area }}</td>
                                      <td>{{ item.problem_cause }}</td>
                                      <td>{{ item.problem_action }}</td>
                                      <td>{{ item.problem_remarks }}</td>
                                      <td v-if="breakdown.status == 'Approved'">
                                          <button @click.prevent="removeProblem(item)" type="button" name="button" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                          </button>
                                      </td>
                                  </tr>
                                  </tbody>
                              </table>
                            </div>
                          </div>


                          <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                                <label for="narration">Narration</label>
                                <textarea name="narration" rows="8" id="narration" v-model="breakdown.narration" class="form-control"></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>




                    <div class="panel panel-default" v-if="breakdown.status == 'Approved'">
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
                  </div>


                  <div class="form-group">
                      <!--<button v-if="status == null || status == 'Saved'" class="btn btn-success" @click.prevent="saveProcess">Save</button>-->
                      <button class="btn btn-success">{{ editing ? 'Save' : 'Process' }}</button>
                      <!-- <button v-if="status == 'Approved'" class="btn btn-warning" @click.prevent="closeCard">Close Job Card</button> -->
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
          problem_area: '',
          problem_cause: '',
          problem_action: '',
          problem_remarks: '',

          breakdown: {
            vehicle_id: null,
            breakdown_area_id: null,
            location: null,
            description: null,
            incident_details: [],
            vehicle_sent: null,
            attended_by: null,
            break_down_recovered: [],
            narration: null,
            breakdown_approved: null,
            breakdown_approved_by: null,
            oims: null,
            status: null,
            vehicle_id: null,
            vehicle_id: null,
            vehicle_id: null
          },

          editing: false,
          status: null,
        };
      },

      created() {
        this.checkState();
      },

      methods: {
        addIncedent() {
          if (this.incedent.length < 2) return;
          this.breakdown.incident_details.push(this.incedent);
          this.incedent = '';
        },

        removeIncedent(incedent) {
          this.breakdown.incident_details.splice(this.breakdown.incident_details.indexOf(incedent), 1);
        },

        addProblem() {
          this.breakdown.break_down_recovered.push({
            problem_area: this.problem_area,
            problem_cause: this.problem_cause,
            problem_action: this.problem_action,
            problem_remarks: this.problem_remarks,
          });

          this.problem_area = '';
          this.problem_cause = '';
          this.problem_action = '';
          this.problem_remarks = '';
        },

        removeProblem(item) {
          this.breakdown.break_down_recovered.splice(this.breakdown.break_down_recovered.indexOf(item), 1);
        },

        checkState() {
          this.$root.isLoading = true;

          if (this.$route.params.id) {
            this.editing = true;

            return http.get("/api/breakdown/" + this.$route.params.id).then(response => {
              this.vehicles = response.vehicles;
              this.areas = response.areas;
              this.drivers = response.drivers;
              this.breakdown = response.breakdown;
              this.$root.isLoading = false;
            });
          }

          http
            .get("/api/breakdown/create")
            .then(response => {
              this.vehicles = response.vehicles;
              this.areas = response.areas;
              this.drivers = response.drivers;
              this.$root.isLoading = false;

              return response;
            });
        },

        store() {
          let request = null;

          if (this.$route.params.id) {
            request = http.put("/api/breakdown/" + this.$route.params.id, this.breakdown);
          } else {
            request = http.post("/api/breakdown", this.breakdown);
          }

          request
            .then(response => {
              alert2(this.$root, [response.message], "success");
              window._router.push({ path: "/wsh/breakdown" });
            })
            .catch(error => {
              alert2(
                this.$root,
                Object.values(JSON.parse(error.message)),
                "danger"
              );
            });
        },

        closeCard() {
          let request = null;

          request = http.post(
            "/api/job-card/" + this.$route.params.id + "/close",
            this.card
          );

          request
            .then(response => {
              alert2(this.$root, [response.message], "success");
              window._router.push({ path: "/wsh/job-card" });
            })
            .catch(error => {
              alert2(
                this.$root,
                Object.values(JSON.parse(error.message)),
                "danger"
              );
            });
        }
      }
    };
</script>
