<template lang="html">
  <div class="container">
    <div class="panel panel-default">
      <div class="panel-heading">
        Employee Mileage
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-8">
            Employee: {{ employee.first_name }} {{ employee.last_name }}
            Id: {{ employee.id }}
            Category: {{ employee.category }}
          </div>

          <div class="col-sm-3">
            <div class="form-group">
              <label for="std_amount">Standard Rate</label>
              <input @keyup="calculateMileageBalance()" type="number" name="" id="std_amount" class="form-control" v-model="standard_rate">
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-6">
            Paid out Mileages
            <table class="table no-wrap">
              <caption>Total Amount Paid: </caption>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Mileage #</th>
                  <th>When</th>
                  <th>Advance</th>
                  <th>Amount</th>
                  <th>Paid By</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(mileage, index) in mileages">
                  <td>{{ index + 1 }}</td>
                  <td>ML - {{ mileage.id }}</td>
                  <td>{{ mileage.created_at }}</td>
                  <td v-if="mileage.is_advance == 1"><span class="label label-success">Yes</span></td>
                  <td v-if="mileage.is_advance == 0"></td>
                  <td>{{ mileage.amount }}</td>
                  <td>{{ mileage.user.first_name}}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th>#</th>
                  <th>Mileage #</th>
                  <th>When</th>
                  <th>Advance</th>
                  <th>Amount</th>
                  <th>Paid By</th>
                </tr>
              </tfoot>
            </table>
          </div>


          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-6">
                <div class="col-sm-12">
                    <div class="form-group">
                      <label for="amount">Amount</label><br>
                      <input type="number" min=0 id="amount" class="form-control" v-model="mileage.amount"><br>
                      <label for="is_advance"><input type="checkbox" id="is_advance" v-model="mileage.is_advance"> Is Advance?</label>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="narration">Narration</label>
                        <textarea name="narration" id="narration" class="form-control input-sm" v-model="mileage.narration"></textarea>
                    </div>
                </div>
              </div>

                <div class="col-sm-6">
                  <div class="col-sm-12">
                    <h5>Advanced Mileage: {{ advanced_mileage }}</h5>
                    <h5>Mileage Paid: {{ mileage_paid }}</h5>
                    <hr>
                    <h2>Balance: Ksh. <strong>{{ mileage_total - advanced_mileage - mileage_paid }}</strong></h2>
                  </div>
              </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group pull-right">
                      <button class="btn btn-success" @click="storeMileage()">Process</button>
                      <router-link to="/ls/mileage" class="btn btn-danger">Back</router-link>
                  </div>
                </div>
              </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      employee: {},
      mileages: [],
      standard_rate: 0,
      mileage_total: 0,
      advanced_mileage: 0,
      mileage_paid: 0,
      mileage: {
        contract_id: '',
        employee_id: '',
        amount: 0,
        narration: '',
        is_advance: false,
      },
    }
  },
  created () {
    this.mileage.contract_id = this.$route.params.contract;
    this.mileage.employee_id = this.$route.params.employee;
    http.get('/api/create_employee_mileage/' + this.$route.params.employee + '/' + this.$route.params.contract).then( (response) => {
      this.employee = response.employee;
      this.mileages = response.mileages;
      this.calculateMileageBalance();
    })
  },
  methods: {
    storeMileage () {
      http.post('/api/ls_employee_mileage', this.mileage).then( (response) => {
        this.mileages = response.mileages;
        this.calculateMileageBalance();
        alert2(this.$root, [response.message], 'success');
        this.mileage.amount = 0;
        this.mileage.narration = '';
      });
    },

    calculateMileageBalance () {
      this.advanced_mileage = 0;
      this.mileage_paid = 0;
      this.mileage_total = this.standard_rate;

      for(let i = 0; i<this.mileages.length; i++) {
        if(this.mileages[i].is_advance == 1) {
          this.advanced_mileage = parseInt(this.advanced_mileage) + parseInt(this.mileages[i].amount);
        }
      }

      for(let i = 0; i<this.mileages.length; i++) {
        if(this.mileages[i].is_advance == 0) {
          this.mileage_paid = parseInt(this.mileage_paid) + parseInt(this.mileages[i].amount);
        }
      }
    }
  }
}
</script>

<style lang="css" scoped>

.payment-box {
  background-color: #efefef;
}
</style>
