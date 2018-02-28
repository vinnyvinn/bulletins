<template>
    <div>
        <div v-html="printout"></div>
        <div class="container hidden-print">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>External Service</strong>
                </div>

                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="job_card_id">Job Card</label>
                                <select disabled required v-model="requisition.job_card_id" name="job_card_id"
                                        id="job_card_id" class="form-control input-sm">
                                    <option v-for="card in cards" :value="card.id">JC-{{ card.id }}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="job_card_id">Supplier</label>
                                <select disabled required v-model="requisition.vendor_id" name="vendor_id" id="vendor_id"
                                        class="form-control input-sm">
                                    <option v-for="vendor in vendors" :value="vendor.DCLink">{{ vendor.Name }} ({{
                                        vendor.Account }})
                                    </option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Vehicle Number</label>
                                <h5>{{ card.vehicle.plate_number }}</h5>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="type">External Service Type</label>
                                <select disabled required v-model="requisition.type" name="type" id="type"
                                        class="form-control input-sm">
                                    <option value="Vehicle">External Vehicle Service</option>
                                    <option value="Parts">External Parts Service</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="approximate_cost">Approximate Cost</label>
                                <input disabled type="number" step="0.01" class="form-control"
                                       v-model="requisition.approximate_cost" id="approximate_cost">
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Make</label>
                                        <h5>{{ card.vehicle.make.name }}</h5>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Model</label>
                                        <h5>{{ card.vehicle.model.name }}</h5>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="mechanic_findings">Mechanic's Findings</label>
                                <textarea disabled v-model="requisition.mechanic_findings" name="mechanic_findings"
                                          id="mechanic_findings" cols="20" rows="5" class="form-control input-sm">{{ card.mechanic_findings }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row" v-if="isParts">

                        <div class="col-sm-12">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in requisition.lines">
                                    <td>{{ index + 1}}</td>
                                    <td>{{ item.item_name }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ item.make }}</td>
                                    <td>{{ item.model }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>

                    <div class="form-group">
                        <button @click.prevent="approve" class="btn btn-success"
                                v-if="service.status == 'Pending Approval'">Approve
                        </button>
                        <button @click.prevent="disapprove" class="btn btn-warning"
                                v-if="service.status == 'Pending Approval'">Disapprove
                        </button>
                        <button @click.prevent="close" class="btn btn-danger" v-if="service.status == 'Approved'">Close
                            External Service
                        </button>
                        <button @click.prevent="back" to="/wsh/external" class="btn btn-danger">Back</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
 </template>

<script>
    export default {
        data() {
            return {
                printout: '',
                service: {},

                parts: [],
                cards: [],
                vendors: [],
                item: {
                    item_id: null,
                    item_name: "",
                    quantity: 1,
                    make: "",
                    model: ""
                },
                requisition: {
                    job_card_id: null,
                    mechanic_findings: "",
                    type: "Vehicle",
                    lines: [],
                    status: "Pending Approval",
                    approximate_cost: null,
                    vendor_id: null
                }
            };
        },

        computed: {
            card() {
                let card = this.cards.filter(e => e.id == this.requisition.job_card_id);

                return card.length ? card[0] : {vehicle: {make: {}, model: {}}};
            },
            isParts() {
                let isParts = this.requisition.type == "Parts";

                if (!isParts) {
                    this.requisition.lines = [];
                }

                return isParts;
            },
            selectedItem() {
                let filtered = this.parts.filter(
                    part => part.StockLink == this.item.item_id
                );

                if (!filtered.length) return {};

                return filtered[0];
            }
        },

        created() {
            this.$root.isLoading = true;
            http
                .get("/api/services/create")
                .then(response => {
                    this.parts = response.parts;
                    this.cards = response.cards;
                    this.vendors = response.vendors;
                    setTimeout(() => {
                        $("#item_id")
                            .select2({
                                placeholder: "Select an option"
                            })
                            .on("change", e => {
                                this.item.item_id = e.target.value;
                                let selectedItem = this.parts.filter(
                                    item => item.StockLink == e.target.value
                                )[0];

                                this.item.item_name =
                                    selectedItem.Description_1 +
                                    "(" +
                                    selectedItem.product_make +
                                    " " +
                                    selectedItem.product_model +
                                    ")";
                            });
                        this.$root.isLoading = false;
                    }, 1000);

                    return response;
                })
                .then(e => this.checkState());
        },

        methods: {
            approve() {
                http
                    .post("/api/services/" + this.$route.params.id + "/approve", {})
                    .then(response => {
                        //show printout
                        this.printout = response.printout;
                       /* setTimeout(() => {
                            window.print();
                        }, 200);
                        setTimeout(() => {
                            alert2(this.$root, [response.message], 'success');
                            window._router.go(-1);
                        }, 1000);*/
                    })
                    .catch(error => {
                        alert2(
                            this.$root,
                            Object.values(JSON.parse(error.message)),
                            "danger"
                        );
                    });
            },
            disapprove() {
                http
                    .post("/api/services/" + this.$route.params.id + "/disapprove", {})
                    .then(response => {
                        alert2(this.$root, [response.message], "success");
                        window._router.go(-1);
                    })
                    .catch(error => {
                        alert2(
                            this.$root,
                            Object.values(JSON.parse(error.message)),
                            "danger"
                        );
                    });
            },
            close() {
                http
                    .post("/api/services/" + this.$route.params.id + "/close", {})
                    .then(response => {
                        alert2(this.$root, [response.message], "success");
                        window._router.go(-1);
                    })
                    .catch(error => {
                        alert2(
                            this.$root,
                            Object.values(JSON.parse(error.message)),
                            "danger"
                        );
                    });
            },

            back() {
                window._router.go(-1);
            },
            addToList() {
                if (parseInt(this.item.quantity) < 1) {
                    alert2(this.$root, ["Please enter a valid quantity"], "danger");
                    return;
                }

                if (!this.item.item_id) {
                    alert2(this.$root, ["Please select an item"], "danger");
                    return;
                }

                let shouldAdd = true;

                this.requisition.lines.map(e => {
                    if (e.item_id == this.item.item_id) {
                        e.requested_quantity =
                            parseInt(e.requested_quantity) +
                            parseInt(this.item.requested_quantity);
                        shouldAdd = false;
                        return true;
                    }

                    return e;
                });

                if (shouldAdd) {
                    this.item.item_name = this.selectedItem.Description_1;
                    this.item.make = this.selectedItem.product_make;
                    this.item.model = this.selectedItem.product_model;
                    this.requisition.lines.push(this.item);
                }

                $("#item_id")
                    .val("null")
                    .trigger("change.select2");
                this.item = {
                    item_id: null,
                    item_name: "",
                    quantity: 1,
                    make: "",
                    model: ""
                };
            },

            remove(item) {
                this.requisition.lines.splice(this.requisition.lines.indexOf(item), 1);
            },

            checkState() {
                if (this.$route.params.id) {
                    http.get("/api/services/" + this.$route.params.id).then(response => {
                        this.requisition = response.service.raw;
                        this.service = response.service;
                    });
                }
            },

            store() {
                let request = null;
                this.requisition.vehicle_number = this.card.vehicle_number;

                if (this.$route.params.id) {
                    request = http.put(
                        "/api/services/" + this.$route.params.id,
                        this.requisition
                    );
                } else {
                    request = http.post("/api/services", this.requisition);
                }

                request
                    .then(response => {
                        alert2(this.$root, [response.message], "success");
                        window._router.go(-1);
                    })
                    .catch(error => {
                        alert2(
                            this.$root,
                            Object.values(JSON.parse(error.message)),
                            "danger"
                        );
                    });
            }
        }
    };
</script>
