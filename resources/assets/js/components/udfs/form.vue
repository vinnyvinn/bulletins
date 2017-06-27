<template lang="html">
  <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <strong>User Defined Fields</strong>
                  </div>

                  <div class="panel-body">
                      <form action="#" role="form" @submit.prevent="store">

                          <div class="form-group">
                              <label for="name">Name</label>
                              <input v-model="udf.name" type="text" class="form-control" id="name" name="name" placeholder="Field Name">
                          </div>

                          <div class="form-group">
                              <label for="input_type">Input Type</label>
                              <select v-model="udf.input_type" class="form-control" name="input_type">
                                <option v-for="input_type in input_types" :value="input_type">{{input_type}}</option>
                              </select>
                          </div>

                          <div v-if="udf.input_type == 'Select'" class="form-group">
                              <label>Options</label>
                              <input v-model="udf.value" type="text" class="form-control" placeholder="Field Name">
                              <span><p><small>Separate Options with Semi-colons(;)</small></p></span>
                          </div>

                          <div class="form-group">
                              <label for="status">Status</label>
                              <select v-model="udf.status" class="form-control" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="module">Module</label>
                              <select v-model="udf.module" class="form-control" name="module" >
                                <option value="" disabled selected>Select Module</option>
                                <option v-for="module in modules" :value="module">{{ module }}</option>
                              </select>
                          </div>

                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea v-model="udf.description" name="description" class="form-control" placeholder="Field description">
                            </textarea>
                          </div>

                          <div class="form-group">
                              <button >Save</button>
                              <router-link to="/udfs" class="btn btn-danger">Back</router-link>
                          </div>
                      </form>

                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    data (){
      return {
        udf: {
          name: '',
          input_type: '',
          status: '1',
          module: '',
          description: '',
          value: ''
        },

        input_types: [],
        modules: [],
      }
    },
    created(){
        this.getInputs ();
    },
    methods: {
        getInputs () {
            http.get('/api/udf/create').then(response => {
              this.input_types = response.inputs;
              this.modules = response.modules;
            }).then(() => {
              if(this.$route.params.id){
                 this.udf._method = 'PUT';
                 http.get('/api/udf/' + this.$route.params.id).then((response) => {
                 this.udf = response;
                });
              }
            });

        },
        store() {
            let request = null;

            if (this.$route.params.id) {
                request = http.put('/api/udf/' + this.$route.params.id, this.udf);
            } else {
                request = http.post('/api/udf', this.udf);
            }

            request.then((response) => {
                console.log(response);
                alert2(this.$root, [response.message], 'success');
                window._router.push({ path: '/udfs' });
            }).catch((error) => {
                alert2(this.$root, Object.values(JSON.parse(error.message)), 'danger');
            });
        }
      }
}
</script>

<style lang="css">
</style>
