<template lang="html">
  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          New Category
        </div>
        <div class="panel-body">
          <form action="#">
            <div class="col-sm-12">
              <div class="input-group">
                <input type="text" class="form-control" v-model="category.category" placeholder="Category Name..." required>
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
          Employee Categories
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
              <tr v-for="(category, index) in employee_categories">
                <td>{{ index + 1}}</td>
                <td>{{ category.category }}</td>
                <td>
                  <span @click="edit(category)" class="btn btn-xs btn-info"><i class="fa fa-pencil"></i></span>
                  <span @click="deleteCategory(category.id)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span>
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
      employee_categories: [],
      category: {
        category: ''
      },
      isEditing: false,
    }
  },
  created() {
    this.fetchCategories();
  },

  methods: {
    fetchCategories () {
      http.get('/api/employee_category'). then((response) => {
        this.employee_categories = response.employee_categories;
      });
    },
    save() {
      this.$root.isLoading = true;
      if(this.isEditing) {
        http.put('/api/employee_category/' +this.category.id, this.category).then((response) => {
          this.fetchCategories();
          alert2(this.$root, [response.message], 'success');
          this.isEditing = false;
          this.category = {};
          this.$root.isLoading = false;
        });
      } else {
        http.post('/api/employee_category', this.category).then((response) => {
          this.category.category = '';
          this.fetchCategories();
          alert2(this.$root, [response.message], 'success');
          this.$root.isLoading = false;
        });
      }

    },
    edit(category) {
      this.category = category;
      this.isEditing = true;
    },
    deleteCategory(id) {
      http.destroy('/api/employee_category/' + id).then((response) => {
        this.fetchCategories();
        alert2(this.$root, [response.message], 'success');
      });
    }
  }
}
</script>

<style lang="css">
</style>
