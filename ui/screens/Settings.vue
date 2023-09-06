<template>
	<div class="wrap-settings">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div>
			<div v-for="(setting, key) of settings" :key="key" class="mb-2">
				<input type="text" v-model="setting.value" :placeholder="setting.placeholder">
			</div>

			<button class="py-2 px-4 leading-3 bg-cyan-600 border border-cyan-900 text-white rounded">Save</button>
		</div>
	</div>
</template>

<script>
import apiClient from "../lib/api-client";

export default {
	name: 'Settings',

	data() {
		return {
			settings: {},
			isLoading: true,
		}
	},

	created() {
		this.fetchSettings()
	},

	methods: {
		async fetchSettings() {
			const { data } = await apiClient.get('/config')

			this.settings = data.data ?? []
			this.isLoading = false
		},
	}
}
</script>
