<template>
    <div class="container">
        <div class="row hidden-print">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Pre-Loading Checklist</strong>
                    </div>
                    <div class="panel-body">
                        <h3 class="text-center">{{ application_name }}</h3>
                        <h4 class="text-center text-uppercase">Mandatory Checklist</h4>
                        <h4 class="text-center text-uppercase" v-if="inspection.created_at"><strong>{{ date2(inspection.created_at) }}</strong></h4>
                        <hr>
                        <form action="#" @submit.prevent="store">

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="journey_id">Journey</label>
                                        <h5><strong> {{inspection.journey_id}}</strong></h5>

                                    </div>
                                </div>

                                <div class="col-sm-3" v-if="inspection.journey.truck">
                                    <div class="form-group">
                                        <label>Driver</label>
                                        <h5><strong>{{ inspection.journey.truck.driver.first_name }} {{ inspection.journey.truck.driver.last_name }}</strong></h5>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Vehicle</label>
                                        <h5><strong>{{ inspection.journey.truck.plate_number }}</strong></h5>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Route</label>
                                        <h5 v-if="inspection.journey.route.source"><strong>{{ inspection.journey.route.source }} to {{ inspection.journey.route.destination }}</strong></h5>
                                    </div>
                                </div>

                            </div>

                            <table class="table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Description</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>The driver has a valid Road Safety Pass and Driving License</td>
                                    <td><label for="item_1"><input disabled v-model="inspection.fields.items.item_1" type="checkbox" id="item_1" name="item_1"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>The vehicle has a valid inspection and insurance certificate</td>
                                    <td><label for="item_2"><input disabled v-model="inspection.fields.items.item_2" type="checkbox" id="item_2" name="item_2"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>The truck has at least one driving mirror intact on each side</td>
                                    <td><label for="item_3"><input disabled v-model="inspection.fields.items.item_3" type="checkbox" id="item_3" name="item_3"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>The truck is fitted with safety belts and they are functioning properly. Was the driver wearing the safety belt?</td>
                                    <td><label for="item_4"><input disabled v-model="inspection.fields.items.item_4" type="checkbox" id="item_4" name="item_4"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>The handbrake and foot brakes are functioning properly. There is no airline pressure leaks, brake boosters are all intact.</td>
                                    <td><label for="item_5"><input disabled v-model="inspection.fields.items.item_5" type="checkbox" id="item_5" name="item_5"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>The truck has a horn and reverse bleeper is functioning properly and audible adequately</td>
                                    <td><label for="item_6"><input disabled v-model="inspection.fields.items.item_6" type="checkbox" id="item_6" name="item_6"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>All lights - indicators and warning hazards are working properly</td>
                                    <td><label for="item_7"><input disabled v-model="inspection.fields.items.item_7" type="checkbox" id="item_7" name="item_7"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>The truck is provided two warning Triangles (Life Savers)</td>
                                    <td><label for="item_8"><input disabled v-model="inspection.fields.items.item_8" type="checkbox" id="item_8" name="item_8"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>The steering box is free from oil leaks, check under the truck to confirm</td>
                                    <td><label for="item_9"><input disabled v-model="inspection.fields.items.item_9" type="checkbox" id="item_9" name="item_9"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>All tyres are in good condition (none of the tyres are worn out or damaged)</td>
                                    <td><label for="item_10"><input disabled v-model="inspection.fields.items.item_10" type="checkbox" id="item_10" name="item_10"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>No wheel has more than one nut missing</td>
                                    <td><label for="item_11"><input disabled v-model="inspection.fields.items.item_11" type="checkbox" id="item_11" name="item_11"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>The truck has a good and adequate tarpaulin (not torn and adequately covers load)</td>
                                    <td><label for="item_12"><input disabled v-model="inspection.fields.items.item_12" type="checkbox" id="item_12" name="item_12"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>13</td>
                                    <td>The truck is provided with two approved metal stoppers sufficiently chained to the prime mover</td>
                                    <td><label for="item_13"><input disabled v-model="inspection.fields.items.item_13" type="checkbox" id="item_13" name="item_13"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>14</td>
                                    <td>The truck is able to self start</td>
                                    <td><label for="item_14"><input disabled v-model="inspection.fields.items.item_14" type="checkbox" id="item_14" name="item_14"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>15</td>
                                    <td>The truck is fitted with a speed governor and the original certificate of installation displays on the truck</td>
                                    <td><label for="item_15"><input disabled v-model="inspection.fields.items.item_15" type="checkbox" id="item_15" name="item_15"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>16</td>
                                    <td>The windscreen & side windows are firmly secured and wipers working properly, not loose and tied by ropes</td>
                                    <td><label for="item_16"><input disabled v-model="inspection.fields.items.item_16" type="checkbox" id="item_16" name="item_16"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>17</td>
                                    <td>Truck is provided with First aid Kit and fire extinguisher</td>
                                    <td><label for="item_17"><input disabled v-model="inspection.fields.items.item_17" type="checkbox" id="item_17" name="item_17"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>18</td>
                                    <td>The hydraulic jack base frame has no visible cracks</td>
                                    <td><label for="item_18"><input disabled v-model="inspection.fields.items.item_18" type="checkbox" id="item_18" name="item_18"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>19</td>
                                    <td>The hydraulic jack/fifth wheel mounting has no missing/loose bolts and is firm/or bulk relieve valve is working normally</td>
                                    <td><label for="item_19"><input disabled v-model="inspection.fields.items.item_19" type="checkbox" id="item_19" name="item_19"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>20</td>
                                    <td>Control Arm Brushes and Shackles in good condition</td>
                                    <td><label for="item_20"><input disabled v-model="inspection.fields.items.item_20" type="checkbox" id="item_20" name="item_20"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>21</td>
                                    <td>Truck is tiffed with GPS and working properly</td>
                                    <td><label for="item_21"><input disabled v-model="inspection.fields.items.item_21" type="checkbox" id="item_21" name="item_21"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>22</td>
                                    <td>Ridges fork free from cracks</td>
                                    <td><label for="item_22"><input disabled v-model="inspection.fields.items.item_22" type="checkbox" id="item_22" name="item_22"> Yes</label></td>
                                </tr>
                                <tr>
                                    <td>23</td>
                                    <td>Gearbox & Engine free from leaks</td>
                                    <td><label for="item_23"><input disabled v-model="inspection.fields.items.item_23" type="checkbox" id="item_23" name="item_23"> Yes</label></td>
                                </tr>
                                </tbody>
                            </table>

                            <h4>Inspector's Comments</h4>
                            <p>{{ inspection.inspectors_comments }}</p>

                            <h4>Suitable for loading?</h4>
                            <p>{{ inspection.suitable_for_loading ? 'Yes' : 'No' }}</p>

                            <div class="form-group">
                                <router-link to="/inspection" class="btn btn-danger">Back</router-link>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="visible-print-block" v-html="printout"></div>
    </div>
</template>

<script>
    export default {
        mounted() {
          http.get('/api/inspection/' + this.$route.params.id).then( response => {
            this.inspection = response.inspection;
          });
        },
        data() {
            return {
                inspection: {
                  fields: {
                    items: {}
                  },
                  journey: {
                    truck: {
                      driver: {}
                    },
                    route: {}
                  }
                },
                printout: '',
                isSupervisor: false,
                isInspector: true,
                application_name: window.Laravel.appname,

            }
        },
        methods: {
            date2(value) {
                return window._date2(value);
            },
        }
  }
</script>
