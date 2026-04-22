import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import { resolve } from 'path';

// https://vitejs.dev/config/
export default defineConfig( ( { mode } ) => {
	const env = loadEnv( mode, __dirname, '' );
	const port = Number.parseInt( env.VITE_PORT || '4000', 10 );

	return {
		server: {
			port: Number.isNaN( port ) ? 4000 : port,
		},
		envDir: __dirname,
		resolve: {
			alias: {
				'@': resolve( __dirname, 'src' ),
			},
		},
		css: {
			devSourcemap: true,
			preprocessorOptions: {
				scss: {
					additionalData: `@use "@/scss/_variables.scss" as *; @use "@/scss/_mixins.scss" as *;`
				}
			}
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
							? `assets/[name].js`
							: `assets/frontend/[name].js`;
					},
					chunkFileNames: `assets/[name].js`,
					assetFileNames: `assets/[name].[ext]`,
					dir: './dist',
				}
			}
		}
	};
} );
