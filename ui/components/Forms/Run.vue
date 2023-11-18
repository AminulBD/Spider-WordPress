<template>
	<div class="fixed flex h-screen inset-0 bg-black/75 z-[500000]">
		<div class="m-auto bg-white p-4 rounded shadow">
			<div>
				<label for="email" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
				<div class="mt-2">
					<textarea v-model="site.keywords" class="block w-full" rows="10" />
				</div>
			</div>

			<div class="grid gap-y-1 mt-4">
				<button :disabled="isLoading" class="py-2 px-4 rounded shadow bg-indigo-600 text-white hover:bg-indigo-500 transition-all" @click="$emit('run')">Run</button>
				<button :disabled="isLoading" class="py-2 px-4 rounded shadow bg-red-600 text-white hover:bg-red-500 transition-all" @click="$emit('cancel')">Cancel</button>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Run',
	props: [ 'site', 'isLoading' ],
}
</script>
