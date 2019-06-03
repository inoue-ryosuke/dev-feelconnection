<template>

<div>
<p>API呼び出しサンプル</p>

<button @click="getJsonSample()">Call API</button>

</div>

</template>

<script>

export default {
  methods: {
    getJsonSample () {
      window.axios.post('/api/get_json_sample', {}
      ).then(function (response) {
        console.log(response);
      });
    }
  }
}

</script>