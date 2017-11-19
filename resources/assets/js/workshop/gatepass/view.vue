<template>
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Gate Pass</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="job_card_id">Job Card</label>
                                <select disabled name="job_card_id" v-model="gatepass.job_card_id" class="form-control input-sm select2" required>
                                  <option v-for="card in jobCards" :value="card.id">JC-{{ card.id }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="plate_number">Plate Number</label>
                                <input disabled type="text" disabled v-model="jobCard.vehicle.plate_number" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="km_reading">KM Reading</label>
                                <input disabled type="number" step="0.01" v-model="gatepass.km_reading" class="form-control input-sm">
                            </div>

                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="external_service_id">External Service Number</label>
                                <select disabled name="external_service_id" v-model="gatepass.external_service_id" class="form-control input-sm select2" required>
                                  <option v-for="card in jobCard.externalServices" :value="card.id">ES-{{ card.id }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="plate_number">Vehicle Make</label>
                                <input disabled type="text" disabled v-model="jobCard.vehicle.make.name" class="form-control input-sm">
                            </div>
                            <div class="form-group">
                                <label for="fuel_reading">Fuel Reading</label>
                                <input disabled type="number" step="0.1" v-model="gatepass.fuel_reading" class="form-control input-sm">
                            </div>

                        </div>

                        <div class="col-sm-3">
                          <div class="form-group">
                              <label for="driver_id">Driver</label>
                              <select disabled name="driver_id" v-model="gatepass.driver_id" class="form-control input-sm select2" required>
                                <option v-for="driver in drivers" :value="driver.id">{{ driver.first_name }} {{ driver.last_name }}</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="plate_number">Vehicle Model</label>
                              <input disabled type="text" disabled v-model="jobCard.vehicle.model.name" class="form-control input-sm">
                          </div>

                          <div class="form-group">
                            <label for="fuel_reading">Approximate Cost</label>
                            <input disabled type="number" step="0.01" v-model="service.approximate_cost" class="form-control input-sm">
                          </div>
                        </div>

                        <div class="col-sm-3">
                          <div class="form-group">
                              <label for="supplier_id">Supplier</label>
                              <select disabled name="supplier_id" id="supplier_id" v-model="gatepass.supplier_id" class="form-control input-sm select2" required>
                                <option v-for="supplier in suppliers" :value="supplier.DCLink">{{ supplier.Name }} ({{ supplier.Account }})</option>
                              </select>
                          </div>
                          <div class="form-group">
                              <label for="plate_number">External Service Type</label>
                              <input disabled type="text" disabled v-model="service.type" class="form-control input-sm">
                          </div>

                        </div>

                    </div>
                    <hr>


                    <div class="row" v-if="service.type == 'Parts'">
                        <div class="col-sm-12">
                          <h4><strong>Parts sent for service.</strong></h4>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th class="text-right">Quantity</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in service.raw.lines">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ item.item_name }}</td>
                                    <td class="text-right">{{ item.quantity }}</td>
                                    <td>{{ item.make }}</td>
                                    <td>{{ item.model }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea disabled type="text" v-model="gatepass.remarks" class="form-control" id="remarks"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group pull-right">
                        <button @click.prevent="approve" class="btn btn-success" v-if="gatepass.status == 'Pending Approval'">Approve</button>
                        <button @click.prevent="disapprove" class="btn btn-warning" v-if="gatepass.status == 'Pending Approval'">Disapprove</button>
                        <button @click.prevent="back" class="btn btn-danger">Back</button>
                    </div>
                </form>

            </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
      created() {
        this.$root.isLoading = true;

        if (this.$route.params.id) {
          return http
            .get("/api/wsh-gatepass/" + this.$route.params.id)
            .then(response => {
              this.jobCards = response.job_cards;
              this.drivers = response.drivers;
              this.gatepass = response.gatepass;
              this.suppliers = response.suppliers;

              this.$root.isLoading = false;
            });
        }

        http
          .get("/api/wsh-gatepass/create/?s=" + window.Laravel.station_id)
          .then(response => {
            this.jobCards = response.job_cards;
            this.drivers = response.drivers;
            this.suppliers = response.suppliers;
            this.$root.isLoading = false;
          });
      },

      mounted() {
        $('input[type="number"]').on("focus", function() {
          this.select();
        });
        $("#gatepass_date")
          .datepicker({
            format: "yyyy-mm-dd",
            startDate: "0d",
            autoclose: true
          })
          .on("change", e => {
            this.gatepass.gatepass_date = e.target.value;
          });
      },

      data() {
        return {
          jobCards: [],
          drivers: [],
          suppliers: [],

          gatepass: {
            station_id: window.Laravel.station_id,
            job_card_id: "",
            external_service_id: "",
            driver_id: "",
            type: "",
            supplier_name: "",
            supplier_id: "",
            fuel_reading: "",
            km_reading: "",
            remarks: "",
            parts: ""
          }
        };
      },

      computed: {
        jobCard() {
          let card = {
            vehicle: {
              model: {},
              make: {}
            },
            externalServices: []
          };
          if (this.gatepass.job_card_id.length < 1) return card;

          let id = parseInt(this.gatepass.job_card_id);
          let cards = this.jobCards.filter(e => e.id == id);

          if (!cards.length) return card;

          return cards[0];
        },

        service() {
          let service = {
            raw: {
              lines: []
            }
          };

          let selectedService = this.jobCard.externalServices.filter(
            e => e.id == this.gatepass.external_service_id
          );

          if (!selectedService.length) return service;

          return selectedService[0];
        }
      },

      methods: {
        back() {
          window._router.go(-1);
        },
        approve() {
          http
            .post("/api/wsh-gatepass/" + this.$route.params.id + "/approve", {})
            .then(response => {
              alert2(this.$root, [response.message], "success");
              window._router.push({ path: "/wsh/gatepass" });
            })
            .catch(error => {
              alert2(
                this.$root,
                Object.values(JSON.parse(error.message)),
                "danger"
              );
            });
        },
        disapprove() {
          http
            .post("/api/wsh-gatepass/" + this.$route.params.id + "/disapprove", {})
            .then(response => {
              alert2(this.$root, [response.message], "success");
              window._router.push({ path: "/wsh/gatepass" });
            })
            .catch(error => {
              alert2(
                this.$root,
                Object.values(JSON.parse(error.message)),
                "danger"
              );
            });
        },
        store() {
          this.$root.isLoading = true;
          let request = null;
          this.gatepass.supplier_name = this.suppliers.filter(
            e => e.DCLink == this.gatepass.supplier_id
          )[0]["Name"];
          this.gatepass.parts = this.service.raw.lines;
          this.gatepass.type = this.service.type;

          let body = Object.assign({}, this.gatepass);

          if (this.$route.params.id) {
            request = axios.put("/api/wsh-gatepass/" + this.$route.params.id, body);
          } else {
            request = axios.post("/api/wsh-gatepass", body);
          }

          request
            .then(response => {
              this.$root.isLoading = false;
              alert2(this.$root, [response.data.message], "success");
              window._router.push({ path: "/wsh/gatepass" });
            })
            .catch(error => {
              this.$root.isLoading = false;
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
