<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Delivery Notes</strong>
                        <router-link to="/delivery/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Del. Note #</th>
                                    <th>Journey #</th>
                                    <th>Loading GW</th>
                                    <th>Loading TW</th>
                                    <th>Loading NW</th>
                                    <th>Offloading GW</th>
                                    <th>Offloading TW</th>
                                    <th>Offloading NW</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="delivery in deliveries">
                                    <td><router-link :to="'/delivery/' + delivery.id">RKS-{{ delivery.id }}</router-link></td>
                                    <td><router-link :to="'/journey/' + delivery.journey_id">JRNY-{{ delivery.journey_id }}</router-link></td>
                                    <td class="text-right">{{ formatNumber(delivery.loading_gross_weight) }}</td>
                                    <td class="text-right">{{ formatNumber(delivery.loading_tare_weight) }}</td>
                                    <td class="text-right">{{ formatNumber(delivery.loading_net_weight) }}</td>
                                    <td class="text-right">{{ formatNumber(delivery.offloading_gross_weight) }}</td>
                                    <td class="text-right">{{ formatNumber(delivery.offloading_tare_weight) }}</td>
                                    <td class="text-right">{{ formatNumber(delivery.offloading_net_weight) }}</td>
                                    <td class="text-center">
                                        <span @click="edit(delivery)" v-if="delivery.status=='Loaded'" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button data-toggle="popover" :data-item="delivery.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Del. Note #</th>
                                    <th>Journey #</th>
                                    <th>Loading GW</th>
                                    <th>Loading TW</th>
                                    <th>Loading NW</th>
                                    <th>Offloading GW</th>
                                    <th>Offloading TW</th>
                                    <th>Offloading NW</th>
                                    <th></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {
            http.get('/api/delivery').then(response => {
                this.deliveries = response.deliveries;
                this.setupConfirm();
                prepareTable();
            });
        },

        data() {
            return {
                deliveries: []
            };
        },

        methods: {
            formatNumber(number) {
                if (! number) {
                    return '';
                }

                return parseFloat(number).toLocaleString();
            },
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },

            edit(journey) {
                window._router.push({path: '/delivery/' + journey.id + '/edit'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/delivery/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.deliveries = response.deliveries;
                    prepareTable();
                    this.$root.isLoading = false;
                    alert2(this.$root, [response.message], 'success');
                }).catch((error) => {
                    this.$root.isLoading = false;
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
