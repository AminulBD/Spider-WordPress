<template>
	<div class="wrap-sites">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div v-if="!sites.length && !isLoading" class="border border-yellow-300 bg-yellow-100 p-2 shadow rounded">
			No sites are available.
			<a href="#" @click.prevent="current = { status: 'inactive', engine: '', config: { limit: 15 } }">Create New Site</a>
		</div>
		<div v-else class="bg-white rounded shadow">
			<div class="flex justify-between items-center px-4 py-2 border-b">
				<h2 class="text-lg font-bold">Sites</h2>
				<div class="items-end">
					<button class="rounded shadow px-3 py-1 bg-indigo-600 text-white hover:bg-indigo-500 transition-all"
							@click="current = { status: 'inactive', engine: '', config: { limit: 15 } }"
					>Create New Site
					</button>
				</div>
			</div>

			<ul role="list" class="divide-y">
				<li v-for="(site, idx) of sites" class="flex justify-between items-center mb-0 p-4 hover:bg-gray-50 transition-all" :key="idx">
					<div class="flex min-w-0 gap-x-4">
						<img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="../icons/engine.svg" alt="">
						<div>
							<p class="flex items-center">
								<span class="text-lg font-semibold leading-6 text-gray-900">{{ site.name }}</span>
								<small class="ml-2 bg-gray-600 rounded px-1.5 text-white inline-block" :class="{ 'bg-green-600': site.status === 'active' }">{{ site.status.toUpperCase() }}</small>
							</p>
							<small class="mt-1">{{ site.engine?.toUpperCase() }}</small>
						</div>
					</div>
					<div class="shrink-0 flex items-end">
						<button @click="nowRun = site">
							<img class="h-6 w-6 flex-none rounded-full bg-gray-50" src="../icons/play-list.svg" alt="Run">
						</button>
						<button @click="current = site">
							<img class="h-6 w-6 flex-none rounded-full bg-gray-50" src="../icons/pen.svg" alt="Edit">
						</button>
						<button @click="this.delete(site.id)">
							<img class="h-6 w-6 flex-none rounded-full bg-gray-50" src="../icons/trash.svg" alt="Delete">
						</button>
					</div>
				</li>
			</ul>
		</div>

		<SiteForm v-if="current" :isLoading="isLoading" :site="current" @save="this.save" @cancel="current = null" />
		<RunForm v-if="nowRun" :isLoading="isLoading" :site="nowRun" @run="this.run" @cancel="nowRun = null" />
	</div>
</template>

<script>
import apiClient from "~/lib/api-client"
import SiteForm from "~/components/Forms/Site.vue"
import RunForm from "~/components/Forms/Run.vue"

export default {
	name: 'Sites',

	components: { SiteForm, RunForm },

	data() {
		return {
			current: null,
			nowRun: null,
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
			const yes = confirm("The site will be deleted permanently. Are you sure?")

			if (!yes) {
				return
			}

			this.isLoading = true
			await apiClient.delete(`/sites/${ id }`)

			await this.fetchSites()
			this.current = null
			this.isLoading = false

		},

		async run() {
			this.isLoading = true
			const keywords =  this.nowRun.keywords.split('\n').map(k => k.trim()).filter(k => k.length > 0)

			await apiClient.post(`/sites/${ this.nowRun.id }`, { keywords })

			this.nowRun = null
			this.isLoading = false
		}
	}
}
</script>
