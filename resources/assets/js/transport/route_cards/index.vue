<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Route Cards</strong>
                        <!--<router-link v-if="$root.can('create-inspection')" to="/inspection/create" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add New</router-link>-->
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>#</th>
                                        <th>Journey #</th>
                                        <th>Generated By</th>
                                        <th>Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="card in cards">
                                        <td>{{ card.id }}</td>
                                        <td>
                                            <a v-if="$root.can('view-route-card')" target="_blank" :href="'/route-card/print/' + card.id">RTC-{{ card.id }}</a>
                                            <span v-else>RTC-{{ card.id }}</span>
                                        </td>
                                        <td>JRNY-{{ card.journey_id }}</td>
                                        <td><span v-if="card.user">{{ card.user.first_name }} {{ card.user.last_name }}</span></td>
                                        <td>{{ date2(card.created_at) }}</td>
                                        <td class="text-center">
                                            <!-- <span v-if="$root.can('edit-route-card')" @click="edit(card)" class="btn btn-xs btn-info">
                                                <i class="fa fa-pencil"></i>
                                            </span>
                                            <button v-if="$root.can('delete-route-card')" data-toggle="popover" :data-item="card.id" class="btn btn-xs btn-danger btn-destroy">
                                                <i class="fa fa-trash"></i>
                                            </button> -->
                                        </td>
                                    </tr>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>#</th>
                                        <th>Journey #</th>
                                        <th>Generated By</th>
                                        <th>Date</th>
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
            http.get('/api/route-card/?s=' + window.Laravel.station_id).then(response => {
                this.cards = response.cards;
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
                 cards: []
            };
        },

        methods: {
            setupConfirm() {
                $('.btn-destroy').off();
                confirm2('.btn-destroy', (element) => {
                    this.destroy(element.dataset.item);
                });
            },
            date2(value) {
                return window._date2(value);
            },

            edit(inspection) {
                window._router.push({ path: '/route-cards/' + inspection.id + '/edit' })
            },

            destroy(id) {
                this.$root.isLoading = true;

                http.destroy('api/route-card/' + id).then(response => {
                    if (response.status != 'success') {
                        this.$root.isLoading = false;
                        alert2(this.$root, [response.message], 'danger');
                        return;
                    }
                    $('table').dataTable().fnDestroy();
                    this.contracts = response.contracts;
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
