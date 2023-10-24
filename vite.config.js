import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
	plugins: [
		vue(),
	],
	resolve: {
		alias: {
			'~': fileURLToPath(new URL('./ui', import.meta.url))
		}
	},
	publicDir: false,
	base: '/wp-content/plugins/spider/',
	build: {
		outDir: 'public',
		manifest: true,
		rollupOptions: {
			input: [
				'ui/admin.js',
			],
		},
	},
	experimental: {
		renderBuiltUrl(filename, type) {
			return { runtime: `window.spider.config.assets_uri + ${ JSON.stringify(filename) }` }
		}
	}
})
