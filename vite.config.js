import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';
import { resolve } from 'path';

// https://vitejs.dev/config/
export default defineConfig( {
	server: {
		port: parseInt( process.env.VITE_PORT ) || 4000,
	},
	plugins: [
		vue(),
		AutoImport( {
			resolvers: [ ElementPlusResolver() ],
		} ),
		Components( {
			resolvers: [ ElementPlusResolver() ],
		} ),
	],
	build: {
		minify: true,
		rollupOptions: {
			input: {
				admin: resolve( __dirname, 'src/main.js' ),
				frontend: resolve( __dirname, 'src/frontend/main.js' ),
			},
			output: {
				entryFileNames: ( { name } ) => {
					return name === 'admin'
						? `assets/[name].js` // Output for admin assets
						: `assets/frontend/[name].js`; // Output for frontend assets
				},
				chunkFileNames: `assets/[name].js`,
				assetFileNames: `assets/[name].[ext]`,
				dir: './dist',
			}
		}
	}
} );