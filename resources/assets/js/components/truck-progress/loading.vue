<template>
    <div class="container">
        <div class="row hidden-print">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Loading</strong>
                    </div>
                    <div class="panel-body">
                        <h3 class="text-center">{{ application_name }}</h3>
                        <h4 class="text-center text-uppercase">Loading</h4>
                        <form action="#" @submit.prevent="store">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h5><strong>Client Name: </strong> {{ trip.contract.client.Name }}</h5>
                                    <h5><strong>Contacts: </strong> {{ trip.contract.client.Telephone }}</h5>
                                    <h5><strong>Route: </strong> {{ trip.route.source }} to  {{ trip.route.destination }}</h5>
                                </div>
                                <div class="col-sm-4">
                                    <h5><strong>Delivery Date: </strong> {{ trip.trip_date }}</h5>
                                    <h5><strong>Journey Number: </strong> {{ trip.id }}</h5>
                                    <h5><strong>Delivery To: </strong> {{ trip.contract.client.Name }}</h5>
                                </div>
                                <div class="col-sm-4">
                                    <h5><strong>Driver: </strong> {{ trip.driver.name }}</h5>
                                    <h5><strong>Vehicle Number: </strong> {{ trip.truck.plate_number }}</h5>
                                    <h5><strong>Trailer Number: </strong> {{ trip.truck.trailer.trailer_number }}</h5>
                                </div>
                            </div>
                            <hr>



                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="items_loaded">Items Loaded</label>
                                        <textarea required v-model="loading.items_loaded" name="items_loaded" id="items_loaded" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="gross_weight">Gross Weight</label>
                                        <div class="input-group">
                                            <input onclick="this.select()" required type="number" min="0" v-model="loading.gross_weight" name="gross_weight" id="gross_weight" class="form-control" aria-describedby="gross_weight_addon">
                                            <span class="input-group-addon" id="gross_weight_addon">KGs</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tare_weight">Tare Weight</label>
                                        <div class="input-group">
                                            <input onclick="this.select()" required type="number" min="0" v-model="loading.tare_weight" name="tare_weight" id="tare_weight" class="form-control" aria-describedby="tare_weight_addon">
                                            <span class="input-group-addon" id="tare_weight_addon">KGs</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="tare_weight">Net Weight</label>
                                        <h5><strong>{{ parseInt(loading.gross_weight) - parseInt(loading.tare_weight) }} KGs</strong></h5>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="number_of_packages">No. Of Packages</label>
                                        <input onclick="this.select()" required type="number" min="0" v-model="loading.number_of_packages" name="number_of_packages" id="number_of_packages" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="weighbridge_number">Weighbridge Ticket Number</label>
                                        <input onclick="this.select()" required type="text" min="0" v-model="loading.weighbridge_number" name="weighbridge_number" id="weighbridge_number" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Process Loading">
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
            this.loading.truck_id = this.$route.params.id;

            http.get('/api/trip/' + this.$route.params.id).then((response) => {
                if (response.status == 'success' && response.trip) {
                    this.trip = response.trip;
                    this.isInspector = response.trip.inspector;
                    this.isSupervisor = response.trip.supervisor;
                }
            });
        },
        data() {
            return {
                printout: '',
                isSupervisor: false,
                isInspector: true,
                application_name: window.Laravel.appname,
                trip: {
                    contract: {
                        client: {}
                    },
                    truck: {
                        trailer: {}
                    },
                    route: {},
                    driver: {}
                },
                loading: {
                    user_id: window.Laravel.user,
                    truck_id: null,
                    inspector_comments: '',
                    supervisors_comments: '',
                    items_loaded: '',
                    number_of_packages: 0,
                    gross_weight: 0,
                    tare_weight: 0,
                    weighbridge_number: 0,
                }
            }
        },

        methods: {
            store() {
                this.$root.isLoading = true;
                http.post('/api/loading/' + this.$route.params.id, this.loading).then(response => {
                    this.printout = response.printout;
                    this.$root.isLoading = false;
                    setTimeout(() => {
                        if (response.shouldPrint) {
                            window.print();
                        }
                        window._router.push({path: '/progress/loading'});
                        alert2(this.$root, [response.message], 'success');
                    }, 500);
                });
            },
        }
    }
</script>
