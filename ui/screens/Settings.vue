<template>
	<div class="wrap-settings">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div>
			<div v-for="(setting, key) of settings" :key="key" class="mb-2">
				<div class="mb-4" v-if="setting.type === 'textarea'">
					<label class="mb-4">{{ setting.label }}</label>
					<textarea
						type="text"
						v-model="setting.value"
						:placeholder="setting.placeholder"
						rows="10"
						class="block w-full p-2"
					></textarea>
				</div>
				<div v-else>
					<label class="mb-4">{{ setting.label }}</label>
					<input class="block w-full py-4 px-2" type="text" v-model="setting.value" :placeholder="setting.placeholder">
				</div>
			</div>

			<button class="py-2 px-4 leading-3 bg-cyan-600 border border-cyan-900 text-white rounded" @click="saveSettings">Save</button>
		</div>
	</div>
</template>

<script>
import apiClient from "~/lib/api-client";

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

			this.settings = data.data ?? {}
			this.isLoading = false
		},

		async saveSettings() {
			this.isLoading = true
			const kv = Object.entries(this.settings).reduce((acc, [key, value]) => {
				acc[key] = value.value

				return acc
			}, {})

			await apiClient.post('/config', kv)

			this.isLoading = false
		}
	}
}
</script>
