<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Delivery Notes</strong>
                        <!--<router-link v-if="$root.can('create-delivery')" to="/delivery/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>-->
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Del. Note #</th>
                                        <th>Journey #</th>
                                        <th>Vehicle #</th>
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
                                        <td>{{ delivery.id }}</td>
                                        <td>
                                            <router-link
                                                    v-if="$root.can('view-delivery')"
                                                    :to="'/delivery/' + delivery.id">
                                                {{ delivery.id_val }}
                                            </router-link>
                                            <span v-else>
                                                {{ delivery.id_val }}
                                            </span>
                                        </td>
                                        <td>
                                            <router-link v-if="$root.can('view-journey')" :to="'/journey/' + delivery.journey_id">JRNY-{{ delivery.journey_id }}</router-link>
                                            <span v-else>JRNY-{{ delivery.journey_id }}</span>
                                        </td>
                                        <td>{{ delivery.journey.truck.plate_number }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.loading_gross_weight) }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.loading_tare_weight) }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.loading_net_weight) }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.offloading_gross_weight) }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.offloading_tare_weight) }}</td>
                                        <td class="text-right">{{ formatNumber(delivery.offloading_net_weight) }}</td>
                                        <td class="text-center">
                                            <span @click="unload(delivery)" v-if="(delivery.status == 'Loaded') && $root.can('create-delivery')" class="btn btn-xs btn-success">Offload</span>
                                            <span @click="edit(delivery)" v-if="$root.can('edit-delivery')" class="btn btn-xs btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </span>
                                            <button v-if="$root.can('delete-delivery')" data-toggle="popover" :data-item="delivery.id" class="btn btn-xs btn-danger btn-destroy">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Del. Note #</th>
                                        <th>Journey #</th>
                                        <th>Vehicle #</th>
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
            http.get('/api/delivery/?s=' + window.Laravel.station_id).then(response => {
                this.deliveries = response.deliveries;
                this.setupConfirm();
                prepareTable(false, [], {
                    "columnDefs": [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        },
                    ],
                    "orderFixed": [0, "desc"],
                });
            });
        },

        data() {
            return {
                deliveries: []
            };
        },

        methods: {
            formatNumber(number) {
                if (!number) {
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

            edit(delivery) {
                window._router.push({ path: '/delivery/' + delivery.id + '/edit' })
            },

            unload(delivery) {
                window._router.push({ path: '/delivery/' + delivery.id + '/unload' })
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/delivery/' + id + '/?s=' + window.Laravel.station_id).then(response => {
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
