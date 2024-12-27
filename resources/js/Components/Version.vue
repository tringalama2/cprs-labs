<script setup>
import {onBeforeMount, ref} from "vue";


let appVersion = ref('');

onBeforeMount(async () => {
    const response = await fetch('/api/app-version');
    const data = await response.json();
    appVersion.value = data.appVersion;
})
</script>

<template>
    <span class="text-xs text-gray-400 sm:text-center">Last Updated: {{ appVersion }}</span>
</template>

<style scoped>

</style>
