<template lang="html">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          New Contract Configuration Field
        </div>
        <div class="panel-body">
          <form action="#">
            <div class="col-sm-12">
              <div class="input-group">
                <input type="text" class="form-control" v-model="field.name" placeholder="Configuration name" required>
                <span class="input-group-btn">
                  <button class="btn btn-success" type="button" @click="save()">Save</button>
                </span>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          Configuration Fields
        </div>
        <div class="panel-body">
          <table class="table">
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in fields">
                <td>{{ index + 1 }}</td>
                <td>{{ field }}</td>
                <td>
                  <span @click="deleteField(field)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
                </td>
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
  data() {
    return {
      fields: [],
      field: {
        name: ''
      },
      isEditing: false,
      mytest: []
    }
  },
  created() {
    this.fetchFields();
  },

  methods: {
    fetchFields () {
      this.$root.isLoading = true;
      http.get('/api/config_field').then((response) => {
        this.fields = response.fields;
        this.filterFields();
        this.$root.isLoading = false;
      });
    },

    filterFields () {
      this.$root.isLoading = true;
      this.fields = Object.keys(this.fields);
      this.fields = this.fields.filter( function (field) {
        return !(field == 'id' | field == 'created_at' | field == 'updated_at' | field == 'contract_id') ;
      });
      this.$root.isLoading = false;
    },

    save() {
      this.$root.isLoading = true;
      if(this.isEditing) {
        http.put('/api/config_field/' +this.field.id, this.field).then((response) => {
          this.fetchFields();
          alert2(this.$root, [response.message], 'success');
          this.isEditing = false;
          this.setting = {};
          this.$root.isLoading = false;
        });
      } else {
        http.post('/api/config_field', this.field).then((response) => {
          this.field.name = null;
          this.fetchFields();
          alert2(this.$root, [response.message], 'success');
          this.$root.isLoading = false;
        });
      }

    },
    edit(setting) {
      this.setting = setting;
      this.isEditing = true;
    },
    deleteField(index) {
      http.destroy('/api/config_delete/' + index).then((response) => {
        this.fetchFields();
        alert2(this.$root, [response.message], 'success');
      });
    }
  }
}
</script>

<style lang="css">
</style>
