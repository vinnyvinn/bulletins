<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Fuel Allocation</strong>
        </div>

        <div class="panel-body">
            <form action="#" role="form" @submit.prevent="store">
                <div class="row">
                    <div class="col-sm-3">

                    </div>

                    <div class="col-sm-3">

                    </div>

                    <div class="col-sm-3">

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
            store() {
                this.$root.isLoading = true;
                let request = null;

                if (this.$route.params.id) {
                    request = http.put('/api/fuel/' + this.$route.params.id, data, true);
                } else {
                    request = http.post('/api/fuel', data, true);
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
