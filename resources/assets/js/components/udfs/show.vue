loc<template lang="html">
  <div class="row">
    <div v-for="field in fields" class="">

      <div v-if="field.datatype == 'Image'" class="">
        <div class="col-md-6">
          <img :src="imageSource(field.value)" alt="" width="200" height="200">
        </div>
      </div>

      <div v-else-if="field.datatype == 'Document'" class="">
        <div class="col-md-6">
          <a :href="imageSource(field.value)"><button type="button" name="button" class="btn btn-sm btn-primary">View document</button></a>
          <p>{{ field.fieldname }}</p>
        </div>        
      </div>

      <div v-if="field.datatype == 'Date'" class="">
        <div class="col-md-6">
          <strong>{{ field.fieldname }}</strong>
        </div>
        <div class="col-md-6">
          {{ processTime(field.value) }}
        </div>
      </div>

      <div v-else-if="field.datatype == 'Short Text'" class="">

        <div class="col-md-6">
          <strong>{{ field.fieldname }}</strong>
        </div>
        <div class="col-md-6">
          <p>{{ field.value }}</p>
        </div>

      </div>

      <div v-else-if="field.datatype == 'Long Text'" class="">

        <div class="col-md-6">
          <strong>{{ field.fieldname }}</strong>
        </div>
        <div class="col-md-6">
          <p>{{ field.value }}</p>
        </div>

      </div>

    </div>
  </div>
</template>

<script>
import moment from 'moment';
export default {
  data () {
    return {
      udfs: [],
      fields: []
    }
  },
  props: ['module','state'],
  created() {
    this.getUdfs();
  },
  computed: {

  },
  methods: {
    getUdfs() {
      http.get('/api/module-udfs/'+this.module).then(response => {
          this.udfs = response;
      }).then(() => {
        for(var key in this.state){
          for(var index in this.udfs){
            var udf = this.udfs[index];
            if(key == udf.slug){
              var datatype = udf.input_type;
              var fieldname = udf.name;
            }
          }
          var value = this.state[key];
          if(datatype){
            this.fields.push({fieldname, datatype, value});
          }
        }
      });
    },
    imageSource (value){
      return '/uploads/' + value;
    },
    processTime(time) {
      return moment(time, 'YYYY-MM-DD').format('MMM Do YYYY');
    }
   }
 }
</script>

<style lang="css">
</style>
