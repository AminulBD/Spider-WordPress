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
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  publicDir: false,
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
    renderBuiltUrl(filename) {
      return { runtime: `window.spiderConfig.assets_uri + ${JSON.stringify(filename)}` }
    }
  }
})
