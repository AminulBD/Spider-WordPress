<template>
	<p>Engines</p>
</template>

<script>
import apiClient from "../lib/api-client";

export default {
	name: 'Engines',

	data() {
		return {
			engines: [],
		}
	},

	created() {
		this.fetchEngines()
	},

	methods: {
		async fetchEngines() {
			const { data } = await apiClient.get('/engines')

			this.engines = data
		},
	}
}
</script>
