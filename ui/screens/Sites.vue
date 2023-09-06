<template>
	<div class="wrap-sites">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div v-if="!sites.length && !isLoading" class="border border-yellow-300 bg-yellow-100 p-2 shadow rounded">
			No sites are available.
		</div>
		<div class="flex flex-wrap -mx-2">
			<div v-for="site of sites" class="w-1/4 p-2">
				<div class="shadow bg-white p-2 rounded h-full">
					<div class="mb-2">
						<div class="text-gray-400">Name</div>
						<div class="text-sm">{{ site.name }}</div>
					</div>
					<div class="mb-2">
						<div class="text-gray-400">Identifier</div>
						<div class="text-sm">{{ site.identifier }}</div>
					</div>
					<div class="mb-2">
						<div class="text-gray-400">Engine</div>
						<div class="text-sm">{{ site.engine }}</div>
					</div>
					<div class="mb-2">
						<div class="text-gray-400">Status</div>
						<span class="border bg-gray-600 rounded px-1.5 text-white text-xs inline-block" :class="{ 'bg-green-600': site.status === 'active' }">{{ site.status }}</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import apiClient from "../lib/api-client";

export default {
	name: 'Sites',

	data() {
		return {
			sites: [],
			isLoading: true,
		}
	},

	created() {
		this.fetchSites()
	},

	methods: {
		async fetchSites() {
			const { data } = await apiClient.get('/sites')

			this.sites = data.data ?? []
			this.isLoading = false
		},
	}
}
</script>
