<template>

<div>
  <p>API呼び出しサンプル</p>
  <button @click="getJsonSample()">Call API</button>
  <br />
  <span>{{ message }}</span>
</div>

</template>

<script>

export default {
  data () {
    return {
      message: ''
    }
  },
  methods: {
    getJsonSample () {
      let self = this;
      window.axios.post('/api/get_json_sample', {}
      ).then(function (response) {
        self.message = response.data;
        console.log(response);
      });
    }
  }
}

</script>