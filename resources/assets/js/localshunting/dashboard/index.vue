<template>
    <div class="container-fluid">
        <div class="row">
          <div class="panel panel-default">
            <div class="panel-heading">
              Local Shunting
            </div>
            <div class="panel-body">
              

            </div>

          </div>

        </div>
    </div>
</template>

<script>
    export default {
        created() {
            this.$root.isLoading = true;
            http.get('/api/dashboard?s=' + window.Laravel.station_id).then(response => {
                this.mapFetchedData(response);
            });
        },

        data() {
            return {
                open_journeys: [],
                not_loaded: [],
                not_fueled: [],
                not_paid: [],
                loading: [],
                offloading: [],
                delivery_notes: [],
                months: [],
                month: '',
            }
        },

        methods: {
            date2(value) {
                return window._date2(value);
            },
            getMonth() {
                let date = new Date();

                return (parseInt(date.getMonth()) + 1).toString() + '-' + date.getYear();
            },

            fetchMonthData() {
                this.$root.isLoading = true;
                http.get('/api/dashboard?month=' + this.month).then(response => {
                    this.mapFetchedData(response);
                });
            },

            mapFetchedData(response) {
//                $('.datatable').dataTable().fnDestroy();
                this.open_journeys = response.open_journeys;
                this.not_fueled = response.not_fueled;
                this.not_loaded = response.not_loaded;
                this.not_paid = response.not_paid;
                this.months = response.months;
                setTimeout(() => {
//                    $('.datatable').dataTable();
                    this.$root.isLoading = false;
                }, 1000);
            }
        }
    }
</script>
