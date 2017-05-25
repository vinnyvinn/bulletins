<template lang="html">
  <div class="container">
      <div class="row">
          <div class="col-md-6 col-md-offset-3">
              <div class="panel panel-default">
                  <div class="panel-heading">
                      <strong>User Defined Fields</strong>
                  </div>
                  {{input_types}}

                  <div class="panel-body">
                      <form action="#" role="form" @submit.prevent="store">

                          <div class="form-group">
                              <label for="plate_number">Name</label>
                              <input v-model="udf.name" type="text" class="form-control" id="name" name="name">
                          </div>

                          <div class="form-group">
                              <label for="make">Input Type</label>
                              <select v-model="udf.input_type" class="form-control" name="input_type" >
                                <option v-for="input_type in input_types" value="">{{input_type}}</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="make">Status</label>
                              <select v-model="udf.status" class="form-control" name="status">
                                <option value="">Active</option>
                                <option value="">Inactive</option>
                              </select>
                          </div>

                          <div class="form-group">
                              <label for="make">Module</label>
                              <select v-model="udf.module" class="form-control" name="module" >
                                <option v-for"system_module in system_modules" value="">{{ system_module.module }}</option>
                              </select>
                          </div>

                          <div class="form-group">
                            <label for="description">Description</label>
                            <textarea v-model="udf.description" name="description" rows="3" cols="30">
                            </textarea>
                          </div>

                          <div class="form-group">
                              <button class="btn btn-success">Save</button>
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
          status: '',
          module: '',
          description: ''
        },
        input_types: '',
      }
    },
    created(){
        this.getInputs ();
    },
    methods: {
        getInputs () {
            http.get('api/udf/create').then(response => {
                this.input_types = response.input_types;
            });
        }
  }
}
</script>

<style lang="css">
</style>
