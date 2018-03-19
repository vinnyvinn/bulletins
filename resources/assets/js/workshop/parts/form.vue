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
                                <select required @change="mapFindings" v-model="requisition.job_card_id" name="job_card_id" id="job_card_id" class="form-control input-sm">
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
                        <div class="col-sm-3">
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

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="item_id">Requested By</label>
                                <select name="item_id" id="emp_id" class="form-control input-sm">
                                    <option value="null" disabled selected>Select ....</option>
                                    <option v-for="employee in employees" :value="employee.id">{{ employee.first_name }} ({{ employee.last_name }})</option>
                                </select>
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
                                    <th>Requested By</th>
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
                                    <td>{{ item.requested_by }}</td>
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
                        <button click.prevent="back" to="/wsh/parts" class="btn btn-danger">Back</button>
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
                    requested_by:''
                },
                requisition: {
                    job_card_id: null,
                    mechanic_findings: '',
                    lines: [],
                    status: 'Pending Approval'
                },
                trucks:[],
                selectparts:[],
                truckmania:[],
                employees:[]
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
            http.get('/api/parts/create?station='+window.Laravel.station_id).then((response) => {

                const byName = response.parts.slice(0);
                this.parts =  byName.sort(function(a,b) {
                    const x = a.Description_1.toLowerCase();
                    const y = b.Description_1.toLowerCase();
                    return x < y ? -1 : x > y ? 1 : 0;
                });

                const byEmpName = response.employees.slice(0);
                this.employees =  byEmpName.sort(function(a,b) {
                    const x = a.first_name.toLowerCase();
                    const y = b.first_name.toLowerCase();
                    return x < y ? -1 : x > y ? 1 : 0;
                });
               /* this.parts = response.parts;
                console.log('by name:');
                console.log(byName);
*/
                console.log("response is ", response);
                this.cards = response.cards;
                this.trucks = response.trucks
                setTimeout(() => {
                    $('#emp_id').select2({
                        placeholder: 'Select an option'
                    }).on('change', (e) => {
                        this.item.item_id = e.target.value;
                        let selectedEmployee = this.employees.filter(item => item.id == e.target.value)[0];

                        this.item.requested_by = selectedEmployee.first_name + ' ' + selectedEmployee.last_name
                    });

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

                return response;
            }).then(e => this.checkState());
        },

        methods: {

          back() {
            window._router.go(-1);
          },
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
                $('#emp_id').val('null').trigger('change.select2');
                this.item = {
                    item_id: null,
                    item_name: '',
                    requested_quantity: 0,
                    approved_quantity: 'Pending',
                    issued_quantity: 'Pending',
                    requested_by: null,
                };
            },

            mapFindings: function (e) {

                //filter a specific
                const truck = this.trucks.filter(truck => truck.plate_number === this.card.vehicle_number);
                this.truckmania = truck;

                if(truck.length){
                    this.selectparts = this.parts.filter((item)=> {
                        return (item.product_make === truck[0].make.name || item.product_model === truck[0].model.name);
                    });
                }

                setTimeout(() => this.requisition.mechanic_findings = this.card.mechanic_findings, 500);
            },

            remove(item) {
                this.requisition.lines.splice(this.requisition.lines.indexOf(item), 1);
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get('/api/parts/' + this.$route.params.id).then((response) => {
                        this.requisition = response.requisition.raw_data;
                    });
                }
            },

            store() {
                if (this.requisition.lines.length == 0) {
                    alert2(this.$root, ['Please an item/s to requisition'], 'danger');
                    return;
                }

                let request = null;
                this.requisition.vehicle_number = this.card.vehicle_number;

                if (this.$route.params.id) {
                    request = http.put('/api/parts/' + this.$route.params.id, this.requisition);
                } else {
                    request = http.post('/api/parts', this.requisition);
                }

                request.then((response) => {
                    alert2(this.$root, [response.message], 'success');
                    window._router.go(-1);
                }).catch((error) => {
                    alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
                });
            }
        }
    }
</script>
