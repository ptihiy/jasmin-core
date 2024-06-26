import { defineConfig } from "vite";
import path from "node:path";

export default defineConfig({
    root: "resources",
    base: "/",
    mode: "development",

    build: {
        outDir: "../public/static",
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: path.resolve(__dirname, "resources/js/main.js"),
        },
    },

    server: {
        strictPort: true,
        port: 5133,
    },

    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./resources"),
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
