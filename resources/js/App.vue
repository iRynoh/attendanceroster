<template>
    <div class="bg-gray-100 flex">
        <aside class="relative bg-theme-600 h-screen w-64 hidden sm:block shadow-xl">
            <div class="p-6 border-b border-theme-600">
                <router-link class="text-white text-3xl font-semibold uppercase hover:text-gray-300" to="/">
                    <template v-if="state.app.logo">
                        <img :src="state.app.logo" :alt="state.app.name"/>
                    </template>
                    <template v-else>
                        {{ state.app.name }}
                    </template>
                </router-link>
            </div>
        </aside>
        <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
            <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
                <main class="w-full flex-grow p-6">
                    <router-view/>
                </main>
                <footer class="w-full bg-white text-center text-sm p-4" v-html="trans('global.phrases.copyright')"></footer>
            </div>
        </div>
    </div>
</template>

<script>
import {computed, onBeforeMount, reactive} from "vue";

import {trans} from '@/helpers/i18n';
import Icon from "@/views/components/icons/Icon";
import {useGlobalStateStore} from "@/stores";
import {useRoute} from "vue-router";
import {useAlertStore} from "@/stores";

export default {
    name: "app",
    components: {
        Icon
    },
    setup() {

        const alertStore = useAlertStore();
        const globalStateStore = useGlobalStateStore();
        const route = useRoute();

        const isLoading = computed(() => {
            var value = false;
            for(var i in globalStateStore.loadingElements) {
                if(globalStateStore.loadingElements[i]){
                    value = true;
                    break;
                }
            }
            return value || globalStateStore.isUILoading;
        })

        const state = reactive({
            app: window.AppConfig,
        });

        onBeforeMount(() => {
            if (route.query.hasOwnProperty('verified') && route.query.verified) {
                alertStore.success(trans('global.phrases.email_verified'));
            }
        });

        return {
            state,
            globalStateStore,
            trans,
            isLoading,
        }
    }
};
</script>
