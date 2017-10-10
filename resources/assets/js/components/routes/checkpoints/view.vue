<template lang="html">
  <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">
            <strong>Route: {{ route.source }} - {{ route.destination }}</strong>
          </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <h5><strong>From</strong></h5>
                    {{ route.source }}
                    <hr>
                </div>
                <div class="col-sm-6">
                    <h5><strong>To</strong></h5>
                    {{ route.destination }}
                    <hr>
                </div>
            </div>

            <h4>
              <strong>Checkpoints</strong>
            </h4>

            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="name">Checkpoint</label>
                  <select name="checkpoint_id" id="checkpoint_id" class="form-control" v-model="checkpoint.checkpoint_id" required>
                    <option v-for="check in checkpoints" :key="check.id" :value="check.id">{{ check.name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="minutes">Minutes</label>
                  <input type="number" onfocus="this.select()" class="form-control" id="minutes" name="minutes" required min="0" v-model="checkpoint.minutes">
                </div>
              </div>

              <div class="col-sm-12">
                <button @click="addCheckpoint" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus"></i> Add checkpoint</button>
                <button @click.prevent="store" class="btn btn-xs btn-success pull-left">Save Changes</button>
                <router-link to="/routes" class="btn btn-danger btn-xs  pull-left">Back</router-link>

              </div>
            </div>

            <table class="table table-stripped">
              <thead>
                <tr>
                  <th>Checkpoint Number</th>
                  <th>Name</th>
                  <th class="text-right">Commute Time (Mins)</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(checkpoint, index) in route.checkpoints">
                  <td>{{ checkpoint.pivot.number }}</td>
                  <td>{{ checkpoint.name }}</td>
                  <td class="text-right">{{ checkpoint.pivot.minutes }}</td>
                  <td class="text-center"><button class="btn btn-danger btn-xs" @click="deleteCheckpoint(checkpoint)"><i class="fa fa-trash"></i></button></td>
                </tr>
              </tbody>
            </table>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data (){
    return {
      allCheckpoints: [],
      checkpoint: {
        checkpoint_id: null,
        minutes: 0,
        number: null,
      },
      route: {
        source: '',
        destination: '',
        distance: '',
        fuel_required: '',
        allowance_amount: 0,
        checkpoints: [],
      },
    }
  },
  computed: {
    checkpoints() {
      let allocated = this.route.checkpoints.map(checkpoint => parseInt(checkpoint.pivot.checkpoint_id));

      return Object.values(this.allCheckpoints).filter(checkpoint => allocated.indexOf(checkpoint.id) === -1)
    },
  },
  created () {
    this.getRoute()
  },
  methods: {
    getRoute() {
      http.get('/api/checkpoints/' + this.$route.params.id).then(response => {
        this.route = response.route;
        this.allCheckpoints = response.checkpoints;
      });
    },

    addCheckpoint() {
        if (! this.checkpoint.checkpoint_id) return;
        let checkpoint = Object.assign({}, this.allCheckpoints[this.checkpoint.checkpoint_id]);
        this.checkpoint.number = this.route.checkpoints.length + 1;
        this.checkpoint.route_id = this.route.id;
        checkpoint.pivot = Object.assign({}, this.checkpoint);

        this.route.checkpoints.push(checkpoint);
        this.checkpoint = {
          checkpoint_id: null,
          minutes: 0,
          number: null,
        };
    },

    deleteCheckpoint(checkpoint) {
      this.route.checkpoints.splice(this.route.checkpoints.indexOf(checkpoint), 1);
    },

    store() {
      let items = this.route.checkpoints.map(point => point.pivot);
      items = {
        checkpoints: items,
        route_id: this.route.id
      };

      http.post('/api/checkpoints', items).then(response => {
        alert2(this.$root, [response.message], 'success');
        window._router.push({ path: '/routes' });
      });
    },
  }
}
</script>
