<template>
	<div class="wrap-sites">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div v-if="!sites.length && !isLoading" class="border border-yellow-300 bg-yellow-100 p-2 shadow rounded">
			No sites are available.
		</div>
		<ul role="list" class="divide-y bg-white rounded shadow">
			<li v-for="(site, idx) of sites" class="flex justify-between mb-0 p-4 hover:bg-gray-50 transition-all" :key="idx">
				<div class="flex min-w-0 gap-x-4">
					<img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="../icons/engine.svg" alt="">
					<div class="min-w-0 flex-auto">
						<p class="text-sm font-semibold leading-6 text-gray-900">{{ site.name }}</p>
						<p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ site.identifier }}</p>
					</div>
				</div>
				<div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
					<p class="text-sm leading-6 text-gray-900">{{ site.engine?.toUpperCase() }}</p>
					<span class="border bg-gray-600 rounded px-1.5 text-white text-xs inline-block" :class="{ 'bg-green-600': site.status === 'active' }">{{ site.status?.toUpperCase() }}</span>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
import apiClient from "~/lib/api-client";

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
