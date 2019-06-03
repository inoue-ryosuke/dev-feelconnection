<template>

<div>
  <h2>Vue サンプル</h2>
  <p><router-link :to="{ name: 'example' }">Example</router-link></p>
  <p><router-link :to="{ name: 'hello' }">Hello</router-link></p>
  <p><router-link :to="{ name: 'xhttp_sample' }">API呼び出しサンプル</router-link></p>

  <div class="container">
    <router-view></router-view>
  </div>
</div>

</template>
