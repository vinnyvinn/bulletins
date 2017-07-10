<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>Truck Activity History</strong>
        <router-link to="/trucks" class="btn btn-success btn-sm pull-right">< Trucks</router-link>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-xs-2">
            <strong>Truck:</strong> {{ truck.plate_number }}
          </div>
          <div class="col-xs-2" v-if="truck.trailer">
            <strong>Trailer:</strong> {{ truck.trailer.trailer_number }}
          </div>
          <div class="col-xs-2">
            <label for="activity_time"><strong>Time</strong></label>
            <!-- <select class="form-control select-2 input-sm" id="activity_time" name="activity_time">
              <option value="Today">Today</option>
              <option value="Yesterday">Yesterday</option>
              <option value="Last Week">Last Week</option>
              <option value="Last Month">Last Month</option>
            </select> -->
          </div>
          <div class="col-xs-2">
            <label for="start_date"><strong>Start Date:</strong></label>
            <input v-model="start_date" type="text" class="datepicker form-control input-sm" id="start_date" name="start_date">
          </div>
          <div class="col-xs-2">
            <label for="end_date"><strong>End Date:</strong></label>
            <input v-model="end_date" type="text" class="datepicker form-control input-sm" id="end_date" name="end_date">
          </div>
        </div>
        <hr>
        <div class="row key">
          <div class="col-xs-2">
            <strong>Color Code</strong>
          </div>
          <div class="col-xs-2 journey">
            Journey Created
          </div>
          <div class="col-xs-2 inspection">
            Inspection Done
          </div>
          <div class="col-xs-2 delivery">
            Delivery Issue
          </div>
          <div class="col-xs-2 fuel">
            Fuel Issued
          </div>
          <div class="col-xs-2 mileage">
            Mileage Issue
          </div>
        </div>
        <hr>
        <div class="row table-responsive">
          <table class="table nowrap table-hover" v-if="activities.length">
            <thead>
              <tr>
                <th>#</th>
                <th>Activity</th>
                <th>Date</th>
                <th>Time</th>
                <th>Doc Number</th>
                <th>Posted By</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(activity, index) in activities" :style="colorCode(activity)"
                  v-if="calculateDateRange(activity.date.date)">
                <td>{{ index + 1 }}</td>
                <td>{{ activity.activity }}</td>
                <td>{{ processDate(activity.date.date) }}</td>
                <td>{{ processTime(activity.date.date) }}</td>
                <td>{{ activity.document_number}}</td>
                <td>{{activity.posted_by}}</td>
              </tr>
            </tbody>
          </table>
          <h5 v-if="!activities.length">This truck doesn't have activity history.</h5>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
export default {
  data () {
    return {
      start_date: '',
      end_date: '',
      date_range: '',
      truck: {
        trailer: {}
      },
      activities: ''
    }
  },
  created(){
    this.$root.isLoading = true;
    http.get('/api/truck-report/'+ this.$route.params.id).then(response => {
      this.truck = response.truck;
      this.activities = response.activities;
      this.sortActivityAscending();
      this.$root.isLoading = false;
      prepareTable();
    });
  },
  mounted () {
    this.setupUI();
  },
  computed: {

  },
  methods: {
    processDate(time) {
      return moment(time, 'YYYY-MM-DD hh-mm-ss').format('LL');
    },
    processTime(time) {
      return moment(time, 'YYYY-MM-DD hh-mm-ss').format('LT');
    },
    sortActivityAscending(){
      this.activities.sort(function (a,b) {
        a = new Date(a.date.date);
        b = new Date(b.date.date);
        return a-b;
      });
    },
    colorCode(activity) {
      if(activity.activity == 'Journey Creation') {
        return 'background-color: #8cd98c; color: #000;';
      } else if(activity.activity == 'Inspection Done'){
        return 'background-color: #999966; color: #000;';
      } else if(activity.activity == 'Delivery Note Issue'){
        return 'background-color: #00ace6; color: #000;';
      } else if(activity.activity == 'Mileage Issue'){
        return 'background-color: #9900cc; color: #000;';
      } else if(activity.activity == 'Fuel Issue'){
        return 'background-color: #ff5c33; color: #000;';
      }
    },
    setupUI () {
      $('.datepicker').datepicker({
          autoclose: true,
          format: 'dd/mm/yyyy',
          todayHighlight: true,
      });

      $('#start_date').datepicker().on('changeDate', (e) => {
          this.start_date = e.date.toLocaleDateString('en-GB');
          $('#end_date').datepicker('setStartDate', e.date);
      });

      $('#end_date').datepicker().on('changeDate', (e) => {
          this.end_date = e.date.toLocaleDateString('en-GB');
      });
    },
    calculateDateRange(activity) {

      if(!this.start_date || !this.end_date) {
        return true
      }

      if(this.start_date > this.end_date) {
        return this.end_date = this.start_date;
      }


      let activity_date = moment(activity, 'YYYY-MM-DD').format('L');

      let start_date = moment(this.start_date, 'DD-MM-YYYY').format('L');

      let end_date = moment(this.end_date, 'DD-MM-YYYY').format('L');

      console.log(activity_date, start_date, end_date);

      if(activity_date >= start_date && activity_date <= end_date ) {
        return true;
      }
    }
  }

}
</script>

<style lang="css" scoped>
.journey{
  background-color: #8cd98c;
  color: #000;
}

.inspection{
  background-color: #999966;
  color: #000;
}

.delivery{
  background-color: #00ace6;
  color: #000;
}

.mileage{
  background-color: #9900cc;
  color: #000;
}

.fuel{
  background-color: #ff5c33;
  color: #000;
}

</style>
