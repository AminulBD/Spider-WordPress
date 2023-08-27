<template>
  <div class="spider-admin-wrapper">
    <p>Hello vue</p>
  </div>
</template>

<script>
import apiClient from './lib/api-client'

export default {
  name: 'SpiderAdmin',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  mounted () {
    apiClient.get('/config').then(({ data }) => {
      console.log(data)
    })
  },
}
</script>

<style scoped>
    div {
        color: red;
    }
</style>
