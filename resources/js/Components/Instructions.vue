<script setup>
import Code from "@/Components/Code.vue";
import {ref} from "vue";

const isMacLike = /(Mac|iPhone|iPod|iPad)/i.test(navigator.platform);
const instructionsTooltip = ref(false);
</script>

<template>
    <ol class="relative text-gray-500 border-s border-gray-200 mb-2">
        <li class="mb-10 ms-6" @mouseout="instructionsTooltip = false">
                <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                    1
                </span>
            <h3 class="font-medium leading-tight text-gray-600">Copy Labs from CPRS</h3>
            <p class="text-sm my-2 leading-7">
                Select "All Tests By Date".
                Use
                <Code>{{ isMacLike ? 'Cmd' : 'Ctrl' }}</Code>
                <Code>A</Code>
                to select all labs then

                <Code>{{ isMacLike ? 'Cmd' : 'Ctrl' }}</Code>
                <Code>C</Code>

                to copy.
            </p>
            <a class="text-sm font-medium text-sky-600 hover:underline" href="#"
               @mouseover="instructionsTooltip = ! instructionsTooltip">Show Me</a>
            <Transition>
                <div v-show="instructionsTooltip" class="absolute mt-4 ms-4 z-10 min-w-max">
                    <div class="p-4 bg-white rounded shadow-lg ring-4 ring-gray-100">
                        <h4 class="mt-1 text-xl font-semibold leading-tight"
                        >CPRS 'Labs' Tab</h4>
                        <div class="mt-4">
                            <img alt="instructions gif" class="mt-4 ring-2 ring-gray-300" src="/instructions.gif"/>
                        </div>
                    </div>
                </div>
            </Transition>
        </li>
        <li class="mb-10 ms-6">
            <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                2
            </span>
            <h3 class="font-medium leading-tight text-gray-600">Paste Into Formatter</h3>
            <p class="text-sm my-2  leading-7">
                Use

                <Code>{{ isMacLike ? 'Cmd' : 'Ctrl' }}</Code>
                <Code>P</Code>

                to paste them into the text box and click "Submit".
            </p>
        </li>
        <li class="ms-6">
            <span class="absolute flex items-center justify-center w-8 h-8 bg-sky-800 text-white rounded-full -start-4 ring-4 ring-sky-600 text-xl font-light">
                3
            </span>
            <h3 class="font-medium leading-tight text-gray-600">Enjoy the Results!</h3>
        </li>
    </ol>
</template>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
