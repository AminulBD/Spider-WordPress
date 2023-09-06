<template>
  <p>Settings</p>
</template>

<script>
import apiClient from "../lib/api-client";

export default {
  name: 'Settings',
  data() {
	return {
	  settings: {  },
	}
  },

  created() {
	this.fetchSettings()
  },

  methods: {
	async fetchSettings() {
	  const { data } = await apiClient.get('/config')

	  this.settings = data
	},
  }
}
</script>
