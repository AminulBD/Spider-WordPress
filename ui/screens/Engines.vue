<template>
	<div class="wrap-engines">
		<div v-if="isLoading" class="text-center font-bold">
			Loading...
		</div>
		<div v-if="!engines.length && !isLoading" class="border border-yellow-300 bg-yellow-100 p-2 shadow rounded">
			No sites are available.
		</div>

		<ul role="list" class="divide-y bg-white rounded shadow">
			<li v-for="(engine, idx) of engines" class="flex justify-between mb-0 p-4 hover:bg-gray-50 transition-all" :key="idx">
				<div class="flex min-w-0 gap-x-4">
					<img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="../icons/engine.svg" alt="">
					<div class="min-w-0 flex-auto">
						<p class="text-sm font-semibold leading-6 text-gray-900">{{ engine.name }}</p>
						<p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ engine.identifier }}</p>
					</div>
				</div>
				<div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
					<p class="text-sm leading-6 text-gray-900">{{ engine.engine }}</p>
					<span class="border bg-gray-600 rounded px-1.5 text-white text-xs inline-block" :class="{ 'bg-green-600': engine.status === 'active' }">{{ engine.status }}</span>
				</div>
			</li>
		</ul>
	</div>
</template>

<script>
import apiClient from "~/lib/api-client";

export default {
	name: 'Engines',

	data() {
		return {
			engines: [],
			isLoading: true,
		}
	},

	created() {
		this.fetchEngines()
	},

	methods: {
		async fetchEngines() {
			const { data } = await apiClient.get('/engines')

			this.engines = data.data ?? []
			this.isLoading = false
		},
	}
}
</script>
