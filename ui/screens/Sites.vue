<template>
	<p>Sites</p>
</template>

<script>
import apiClient from "../lib/api-client";

export default {
	name: 'Sites',

	data() {
		return {
			sites: [],
		}
	},

	created() {
		this.fetchSites()
	},

	methods: {
		async fetchSites() {
			const { data } = await apiClient.get('/sites')

			this.sites = data
		},
	}
}
</script>
