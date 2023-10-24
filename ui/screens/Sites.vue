<template>
	<div class="wrap-sites">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div v-if="!sites.length && !isLoading" class="border border-yellow-300 bg-yellow-100 p-2 shadow rounded">
			No sites are available. <a href="#" @click.prevent="current = {}">Create New Site</a>
		</div>
		<div v-else class="bg-white rounded shadow">
			<div class="flex justify-between items-center p-4 border-b">
				<h2 class="text-lg font-bold">Sites</h2>
				<div class="items-end">
					<button class="rounded shadow px-3 py-1 bg-indigo-600 text-white hover:bg-indigo-500 transition-all"
							@click="current = {}"
					>Create New Site
					</button>
				</div>
			</div>

			<ul role="list" class="divide-y">
				<li v-for="(site, idx) of sites" class="flex justify-between items-center mb-0 p-4 hover:bg-gray-50 transition-all hover:cursor-pointer" :key="idx" @click="current = site">
					<div class="flex min-w-0 gap-x-4">
						<img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="../icons/engine.svg" alt="">
						<div class="min-w-0 flex-auto">
							<p class="text-sm font-semibold leading-6 text-gray-900">{{ site.name }}</p>
							<small class="mt-1">{{ site.engine?.toUpperCase() }}</small>
						</div>
					</div>
					<div class="shrink-0 flex flex-col items-end">
						<button @click.stop="this.delete(site.id)">
							<img class="h-6 w-6 flex-none rounded-full bg-gray-50" src="../icons/trash.svg" alt="">
						</button>
						<span class="border bg-gray-600 rounded px-1.5 text-white text-xs inline-block" :class="{ 'bg-green-600': site.status === 'active' }">{{ site.status?.toUpperCase() }}</span>
					</div>
				</li>
			</ul>
		</div>

		<SiteForm v-if="current" :isLoading="isLoading" :site="current" @save="this.save" @cancel="current = null" />
	</div>
</template>

<script>
import apiClient from "~/lib/api-client";
import SiteForm from "~/components/Forms/Site.vue";

export default {
	name: 'Sites',

	components: { SiteForm },

	data() {
		return {
			current: null,
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

		async save() {
			this.isLoading = true
			const { name, engine, config, status } = this.current

			if (this.current.id) {
				await apiClient.put(`/sites/${ this.current.id }`, { name, engine, config, status })
			} else {
				await apiClient.post('/sites', { name, engine, config, status })
			}

			await this.fetchSites()
			this.current = null
			this.isLoading = false
		},

		async delete(id) {
			this.isLoading = true
			await apiClient.delete(`/sites/${ id }`)

			await this.fetchSites()
			this.current = null
			this.isLoading = false
		}
	}
}
</script>
