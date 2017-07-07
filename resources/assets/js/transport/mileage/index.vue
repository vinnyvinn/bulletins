<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Mileage Allocation</strong>
                        <router-link to="/mileage/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table no-wrap">
                                <thead>
                                <tr>
                                    <th>Mileage #</th>
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
                                    <td><router-link :to="'/mileage/' + mileage.id">MLG-{{ mileage.id }}</router-link></td>
                                    <td><router-link :to="'/journey/' + mileage.journey_id">JRNY-{{ mileage.journey_id }}</router-link></td>
                                    <td>{{ mileage.mileage_type }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.standard_amount) }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.requested_amount) }}</td>
                                    <td class="text-right">{{ formatNumber(mileage.approved_amount) }}</td>
                                    <td class="text-center">
                                        <span @click="edit(mileage)" v-if="!mileage.approved_amount" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                                        <button data-toggle="popover" :data-item="mileage.id" class="btn btn-xs btn-danger btn-destroy">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>

                                <tfoot>
                                <tr>
                                    <th>Mileage #</th>
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
            http.get('/api/mileage').then(response => {
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

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/mileage/' + id).then(response => {
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
