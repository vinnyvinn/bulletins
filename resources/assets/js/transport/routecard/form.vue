<template lang="html">
  <div class="panel panel-default">
    <div class="panel-heading">
      Route Card
    </div>
    <div class="panel-body">
      <form role="form" action="#" method="post" @submit.prevent="store">
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label for="journey_id">Select Delivery Note</label>
              <select class="" name="delivery_note" v-model="route_card.delivery_note" class="form-control input-sm select2" required @change="selectDeliveryNote">
                <option v-for="delivery_note in delivery_notes" :value="delivery_note.id">DL_NOTE-{{ delivery_note.id }}</option>
              </select>
            </div>


          </div>
        </div>
      </form>
    </div>
  </div>

</template>

<script>
export default {
  created() {
      http.get('/api/route-card/create').then((response) => {
          this.delivery_notes = response.delivery_notes;
      });
  },
  data() {
    return{
      delivery_notes: [],

      route_card: {
        journey_id: '',
        vehicle: '',
        delivery_note: '',
        customer: '',
        job_date: '',
        job_details: '',
        invoice_currency: '',
        route_no: '',
        bi_directional: '',
        return_route: '',
        start_point: '',
        driver: '',
        turn_boy: '',
        total_delay: ''
      }
    }
  },
  computed: {
    selectDeliveryNote() {
      let delivery_note = this.delivery_notes.filter(e => e.id == this.route_card.delivery_note);
      if (delivery_note.length) {
        this.route_card.journey_id = JSON.parse(JSON.Stringify(delivery_note[0].journey_id));
        this.route_card.vehicle = JSON.parse(JSON.stringify(delivery_note[0].journey.truck.id));
        this.route_card.driver = JSON.parse(JSON.stringify(delivery_note[0].journey.driver.id));
        this.route_card.job_date = JSON.parse(JSON.stringify(delivery_note[0].journey.job_date));
        this.route_card.job_details = JSON.parse(JSON.stringify(delivery_note[0].journey.job_description));
        this.route_card.route_no = JSON.parse(JSON.stringify(delivery_note[0].journey.route.id));
        if(delivery_note[0].journey.contract) {
          this.route_card.customer = JSON.parse(JSON.stringify(delivery_note[0].journey.contract.client_id));
        }

        return this.route_card.delivery_note = JSON.parse(JSON.stringify(delivery_note[0].id));
      }
    },
  }
}
</script>

<style lang="css">
</style>
