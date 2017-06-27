<template>
    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>New Parts Request</strong>
            </div>

            <div class="panel-body">
                <form action="#" role="form" @submit.prevent="store">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="job_card_id">Job Card</label>
                                <select @change="mapFindings" v-model="requisition.job_card_id" name="job_card_id" id="job_card_id" class="form-control input-sm">
                                    <option v-for="card in cards" :value="card.id">JC-{{ card.id }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Vehicle Number</label>
                                <h5>{{ card.vehicle_number }}</h5>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mechanic_findings">Mechanic's Findings</label>
                                <textarea name="mechanic_findings" id="mechanic_findings" cols="20" rows="5" class="form-control input-sm">{{ card.mechanic_findings }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="item_id">Spare</label>
                                <select name="item_id" id="item_id" class="form-control input-sm">
                                    <option value="null" disabled selected>Select Spare</option>
                                    <option v-for="item in parts" :value="item.StockLink">{{ item.Description_1 }} ({{ item.product_make }} {{ item.product_model }})</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="requested_quantity">Quantity</label>
                                <input number type="number" min="0" class="form-control"
                                       onclick="this.select()"
                                       name="requested_quantity" id="requested_quantity" v-model="item.requested_quantity">
                            </div>
                        </div>

                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Actions</label>
                                <a @click="addToList" class="btn btn-success btn-block">Add to Requisition</a>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Requested</th>
                                    <th>Approved</th>
                                    <th>Issued</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in requisition.lines">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ item.item_name }}</td>
                                    <td>{{ item.requested_quantity }}</td>
                                    <td>{{ item.approved_quantity }}</td>
                                    <td>{{ item.issued_quantity }}</td>
                                    <td>
                                        <a @click="remove(item)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <button class="btn btn-success">Save</button>
                        <router-link to="/job-card" class="btn btn-danger">Back</router-link>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                parts: [],
                cards: [],
                item: {
                    item_id: null,
                    item_name: '',
                    requested_quantity: 0,
                    approved_quantity: 'Pending',
                    issued_quantity: 'Pending',
                },
                requisition: {
                    job_card_id: null,
                    mechanic_findings: '',
                    lines: [],
                    status: 'Pending Approval'
                }
            };
        },

        computed: {
            card() {
                let card = this.cards.filter(e => e.id == this.requisition.job_card_id);

                return card.length ? JSON.parse(card[0].raw_data) : {};
            },
        },

        created() {
            this.$root.isLoading = true;
            http.get('/api/parts/create').then((response) => {
                this.parts = response.parts;
                this.cards = response.cards;
                setTimeout(() => {
                    $('#item_id').select2({
                        placeholder: 'Select an option'
                    }).on('change', (e) => {
                        this.item.item_id = e.target.value;
                        let selectedItem = this.parts.filter(item => item.StockLink == e.target.value)[0];

                        this.item.item_name = selectedItem.Description_1 + '(' + selectedItem.product_make +
                            ' ' + selectedItem.product_model + ')'
                    });
                    this.$root.isLoading = false;
                }, 1000);
            });
        },

        mounted() {
            this.checkState();
        },

        methods: {
            addToList() {
                if (parseInt(this.item.requested_quantity) < 1) {
                    alert2(this.$root, ['Please enter a valid quantity'], 'danger');
                    return;
                }

                let shouldAdd = true;

                this.requisition.lines.map(e => {
                    if (e.item_id === this.item.item_id) {
                        e.requested_quantity = parseInt(e.requested_quantity) + parseInt(this.item.requested_quantity);
                        shouldAdd = false;
                        return true;
                    }

                    return e;
                });

                if (shouldAdd) this.requisition.lines.push(this.item);

                $('#item_id').val('null').trigger('change.select2');
                this.item = {
                    item_id: null,
                    item_name: '',
                    requested_quantity: 0,
                    approved_quantity: 'Pending',
                    issued_quantity: 'Pending',
                };
            },
            mapFindings() {
                setTimeout(() => this.requisition.mechanic_findings = this.card.mechanic_findings, 500);
            },

            remove(item) {
                this.requisition.lines.splice(this.requisition.lines.indexOf(item), 1);
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/parts/' + this.$route.params.id).then((response) => {
                        this.card = response.card.raw_data;
                    });
                }
            },

            store() {
                let request = null;
                this.requisition.vehicle_number = this.card.vehicle_number;

                if (this.$route.params.id) {
                    request = http.put('/api/parts/' + this.$route.params.id, this.requisition);
                } else {
                    request = http.post('/api/parts', this.requisition);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.push({ path: '/job-card' });
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
