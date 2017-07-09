<template lang="html">
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <router-link to="/contracts" class="btn btn-danger btn-xs">Back</router-link>
                    <strong>Contract for {{ contract.name }}</strong>
                </div>

                <div class="panel-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#details" aria-controls="home" role="tab" data-toggle="tab">Contract Details</a>
                        </li>
                        <li role="presentation">
                            <a href="#journeys" aria-controls="profile" role="tab" data-toggle="tab">Journeys</a>
                        </li>
                        <li role="presentation">
                            <a href="#deliveries" aria-controls="profile" role="tab" data-toggle="tab">Deliveries</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="details">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5><strong>Billing Rate</strong></h5>
                                    {{ contract.rate }}
                                    <hr>

                                    <h5><strong>Amount</strong></h5>
                                    {{ contract.amount }}
                                    <hr>

                                </div>
                                <div class="col-sm-6">
                                    <h5><strong>Start Date</strong></h5>
                                    {{ contract.start_date }}
                                    <hr>

                                    <h5><strong>End Date</strong></h5>
                                    {{ contract.end_date }}
                                    <hr>
                                </div>
                            </div>

                            <show-udfs module="Contracts" :state="contract"></show-udfs>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="journeys">...</div>
                        <div role="tabpanel" class="tab-pane" id="deliveries">...</div>
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
        contract: {
            name: null,
            rate: 'Per Tonne',
            amount: null,
            client_id: null,
            stock_item_id: null,
            route_id: null,
            start_date: null,
            end_date: null,
            quantity: null,
        }
    }
  },
  created () {
    this.getDriver()
  },
  methods: {
    getDriver () {
      http.get('/api/contract/' + this.$route.params.id).then(response => {
        this.contract = response.contract;
      });
    }
  }
}
</script>
