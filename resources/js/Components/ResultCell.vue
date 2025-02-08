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
    'criticalResult': critical,
    'abnormalResult': flag,
    'normalResult': !(critical || flag),
}))
</script>

<template>
    <td :class="classObject">{{ result }}</td>
</template>
