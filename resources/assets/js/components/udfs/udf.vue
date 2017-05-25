<template lang="html">
  <div>
    <div v-for="udf in udfs">
      <div v-if="udf.input_type === 'Text'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="text"
        class="form-control text-uppercase"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name">
      </div>

      <div v-else-if="udf.input_type === 'TextArea'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <textarea
        :name="udf.slug"
        :id="udf.slug" rows="3" cols="30"
        class="form-control">{{ udf.name }}
        </textarea>
      </div>

      <div v-else-if="udf.input_type === 'Number'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="number"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name">
      </div>

      <div v-else-if="udf.input_type === 'File'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="file"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name">
      </div>

      <div v-else-if="udf.input_type === 'DateTime'" class="form-group">
        <label :for="udf.slug">{{ udf.name }}</label>
        <input type="Date"
        class="form-control"
        :id="udf.slug"
        :name="udf.slug"
        :placeholder="udf.name">
      </div>

      <div v-else="udf.input_type === 'Select'" class="form-group">
        <label :for="udf.slug" class="control-label">{{udf.name}} </label>
        <select class="form-control" :name="udf.slug">
          <option value="">Select {{ udf.name }}</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      udfs: [{
        name: 'Text',
        slug: 'demo',
        input_type: 'Text'
      },
      {
        name: 'Text Area',
        slug: 'nicedemo',
        input_type: 'TextArea'
      },
      {
        name: 'File',
        slug: 'nicefile',
        input_type: 'File'
      },
      {
        name: 'DateTime',
        slug: 'nicedemo',
        input_type: 'DateTime'
      },
      {
        name: 'Demo',
        slug: 'demo',
        input_type: 'Select'
      }]
    }
  },
  props: ['module'],
  mounted () {
    this.getUdfs()
  },
  methods: {
    getUdfs () {
        http.get('/api/udf').then(response => {
            this.udfs = response.udfs;
        });
      }
    }
  }
</script>

<style lang="css">
</style>
