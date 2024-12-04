import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import { v4wp } from '@kucrut/vite-for-wp';
import { wp_scripts } from '@kucrut/vite-for-wp/plugins';
import Icons from 'unplugin-icons/vite';
import IconsResolver from 'unplugin-icons/resolver';
import Components from 'unplugin-vue-components/vite';

export default defineConfig({
    plugins: [
        v4wp({
            input: {
                app: 'assets/app.js',
            },
            outDir: 'build',
        }),
        // wp_scripts(),
        vue(),
        Components({
            resolvers: [
                IconsResolver(),
            ],
        }),
        Icons({ autoInstall: true, scale: 1 }),
    ],
    css: {
        lightningcss: true,
    },
    build: {
        target: 'modules',
        sourcemap: false,
    },
});