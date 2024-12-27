<script setup>
import {computed} from "vue";

const props = defineProps({
    result: {
        type: String,
        default: ''
    },
    flag: {
        type: String,
        default: ''
    },
})

const critical = props.flag?.includes('*');
const flag = props.flag?.match(/^([LH])$/) && !critical;
const classObject = computed(() => ({
    'bg-red-500 text-red-950 group-hover:bg-sky-500 font-bold': critical,
    'bg-red-300 text-red-900 group-hover:bg-sky-400 font-bold': flag,
    'group-hover:bg-sky-200 bg-white': !(critical || flag),
}))
</script>

<template>
    <td :class="classObject"
        class="border-r border-gray-500 px-2 text-center whitespace-nowrap">{{ result }}
    </td>
</template>

<style scoped>

</style>
