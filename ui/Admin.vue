<template>
	<div class="spider-admin-wrapper">
		<nav>
			<ul class="flex">
				<li class="block">
					<a href="#spiders" :class="{ 'bold text-green-500': isActive('spiders') }" @click="switchTo('spiders')">Spiders</a>
				</li>
				<li class="block">
					<a href="#engines" :class="{ 'bold text-green-500': isActive('engines') }" @click="switchTo('engines')">Engines</a>
				</li>
				<li class="block">
					<a href="#settings" :class="{ 'bold text-green-500': isActive('settings') }" @click="switchTo('settings')">Settings</a>
				</li>
			</ul>
		</nav>
		<main>
			<p>{{ screen }}</p>
		</main>
	</div>
</template>

<script>
import apiClient from './lib/api-client'

export default {
	name: 'SpiderAdmin',
	data() {
		return {
			screen: 'spiders'
		}
	},
	mounted() {
		apiClient.get('/config').then(({ data }) => {
			console.log(data)
		})
	},
	computed: {},
	methods: {
		isActive(current) {
			return this.screen === current
		},
		switchTo(screen) {
			this.screen = screen
		}
	}
}
</script>
