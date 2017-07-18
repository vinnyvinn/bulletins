<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Mileage Allocation</strong>
                        <!--<router-link v-if="$root.can('create-mileage')" to="/mileage/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>-->
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Mileage #</th>
                                    <th>Status</th>
                                    <th>Journey #</th>
                                    <th>Mileage Type</th>
                                    <th class="text-right">Std Amount</th>
                                    <th class="text-right">Requested Amount</th>
                                    <th class="text-right">Approved Amount</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="mileage in mileages">
                                    <td>
                                        <router-link v-if="$root.can('view-mileage')" :to="'/mileage/' + mileage.id">MLG-{{ mileage.id }}</router-link>
                                        <span v-else>MLG-{{ mileage.id }}</span>
                                    </td>
                                    <td>
                                        <span v-if="mileage.status == 'Approved'" class="label label-success">Approved</span>
                                        <span v-else class="label label-info">Pending</span>
                                    </td>
                                    <td>
                                        <router-link v-if="$root.can('create-journey')" :to="'/journey/' + mileage.journey_id">JRNY-{{ mileage.journey_id }}</router-link>
                                        <span v-else>JRNY-{{ mileage.journey_id }}</span>
                                    </td>
                                    <td>{{ mileage.mileage_type }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.standard_amount) }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.requested_amount) }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.approved_amount) }}</td>
                                    <td class="text-right">
                                        <router-link  class="btn btn-success btn-xs" :to="'/mileage/create/'+ mileage.journey_id" v-if="$root.can('create-mileage') && (mileage.journey.status != 'Closed')">
                                          ADD MILEAGE
                                        </router-link>
                                        <span @click="edit(mileage)" v-if="$root.can('edit-mileage')" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button v-if="$root.can('delete-mileage')" data-toggle="popover" :data-item="mileage.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Mileage #</th>
                                    <th>Status</th>
                                    <th>Journey #</th>
                                    <th>Mileage Type</th>
                                    <th class="text-right">Std Amount</th>
                                    <th class="text-right">Requested Amount</th>
                                    <th class="text-right">Approved Amount</th>
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
            http.get('/api/mileage/?s=' + window.Laravel.station_id).then(response => {
                this.mileages = response.mileages;
                this.setupConfirm();
                prepareTable();
            });
        },

        data() {
            return {
                mileages: []
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
                window._router.push({path: '/mileage/' + journey.id + '/edit'})
            },

            approve(journey) {
                window._router.push({path: '/mileage/' + journey.id + '/approve'})
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('/api/mileage/' + id + '/?s=' + window.Laravel.station_id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.mileages = response.mileages;
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
