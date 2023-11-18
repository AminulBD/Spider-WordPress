<template>
	<div class="fixed flex h-screen inset-0 bg-black/75 z-[500000]">
		<div class="m-auto bg-white p-4 rounded shadow">
			<div class="grid gap-x-3 gap-y-4 grid-cols-12">
				<div class="col-span-6">
					<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
					<div class="mt-2">
						<input v-model="site.name" type="text" class="block w-full">
					</div>
				</div>

				<div class="col-span-6">
					<label class="block text-sm font-medium leading-6 text-gray-900">Engine</label>
					<div class="mt-2">
						<select class="block w-full" v-model="site.engine">
							<option value="">Select an engine</option>
							<option v-for="(engine, idx) of engines" :key="idx" :value="engine.id">{{ engine.name }}</option>
						</select>
					</div>
				</div>

				<template v-for="(_, key) of site.config">
					<div class="col-span-full">
						<label class="block text-sm font-medium leading-6 text-gray-900">Config</label>
						<div class="mt-2">
							<input v-model="site.config[key]" :placeholder="key" type="text" class="block w-full">
						</div>
					</div>
				</template>

				<fieldset class="col-span-full">
					<legend class="text-sm font-medium leading-6 text-gray-900">Status</legend>
					<div class="mt-2 flex gap-x-3">
						<div class="flex items-center gap-x-2">
							<input v-model="site.status" :id="`site-form-status-active-${site.id || 'new'}`" value="active" type="radio">
							<label :for="`site-form-status-active-${site.id || 'new'}`">Active</label>
						</div>
						<div class="flex items-center gap-x-2">
							<input v-model="site.status" :id="`site-form-status-inactive-${site.id || 'new'}`" value="inactive" type="radio">
							<label :for="`site-form-status-inactive-${site.id || 'new'}`">Inactive</label>
						</div>
					</div>
				</fieldset>
			</div>

			<div class="grid gap-y-1 mt-4">
				<button :disabled="isLoading" class="py-2 px-4 rounded shadow bg-indigo-600 text-white hover:bg-indigo-500 transition-all" @click="$emit('save')">Save</button>
				<button :disabled="isLoading" class="py-2 px-4 rounded shadow bg-red-600 text-white hover:bg-red-500 transition-all" @click="$emit('cancel')">Cancel</button>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Site',
	props: [ 'site', 'isLoading' ],

	data() {
		return {
			engines: [
				{ name: 'Google', id: 'google' },
				{ name: 'Bing', id: 'bing' },
				{ name: 'WordPress', id: 'wordpress' },
			]
		}
	}
}
</script>
