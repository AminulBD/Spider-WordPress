<template>
	<div class="spider-admin-wrapper pt-3.5">
		<nav>
			<ul class="flex border-b-2">
				<li class="mb-0">
					<a
						href="#sites"
						class="block py-2 mr-6 border-b-2 -mb-[2px] focus:outline-0 font-bold transition-all"
						:class="{ 'border-b-black': isActive('sites') }"
						@click="switchTo('sites')"
					>Sites</a>
				</li>
				<li class="mb-0">
					<a
						href="#engines"
						class="block py-2 mr-6 border-b-2 -mb-[2px] focus:outline-0 font-bold transition-all"
						:class="{ 'border-b-black': isActive('engines') }"
						@click="switchTo('engines')"
					>Engines</a>
				</li>
				<li class="mb-0">
					<a href="#settings"
					   class="block py-2 mr-6 border-b-2 -mb-[2px] focus:outline-0 font-bold transition-all"
					   :class="{ 'border-b-black': isActive('settings') }"
					   @click="switchTo('settings')"
					>Settings</a>
				</li>
			</ul>
		</nav>
		<main class="pt-4">
			<Sites id="sites" v-if="isActive('sites')" />
			<Engines id="engines" v-if="isActive('engines')" />
			<Settings id="settings" v-if="isActive('settings')" />
		</main>
	</div>
</template>

<script>
import Sites from '~/screens/Sites.vue'
import Engines from '~/screens/Engines.vue'
import Settings from '~/screens/Settings.vue'

export default {
	name: 'SpiderAdmin',
	components: { Sites, Engines, Settings },

	data() {
		return {
			screen: 'sites',
		}
	},

	created() {
		const url = new URL(window.location.href)
		const hash = url.hash.slice(1)

		this.screen = hash || 'sites'
	},

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
