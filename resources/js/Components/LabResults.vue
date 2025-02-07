<script setup>
import ResultCell from "@/Components/ResultCell.vue";
import Modal from "@/Components/Modal.vue";
import Unparsable from "@/Components/Unparsable.vue";
import {reactive, ref, shallowReactive} from "vue";
import {LabDirector} from "@/LabsDisplay/index.js";

const errors = reactive([]);
const rawLabs = ref('');
const isLoading = ref(false);
const showingResults = ref(false);
const labResults = shallowReactive([]);
const unparsableRows = shallowReactive([]);
const labels = shallowReactive({});
const panels = shallowReactive({});
const dateTimeHeaders = shallowReactive([]);


const resetForm = () => {
    errors.splice(0);
    rawLabs.value = '';
}

const formatLabs = () => {
    errors.splice(0);

    if (rawLabs.value === "") {
        errors.push('Please paste your labs below.');
        return;
    }

    isLoading.value = true;

    const labCount = getLabs();

    if (labCount === 0) {
        errors.push('No labs found.');
        showingResults.value = false;
        isLoading.value = false;
        return
    }

    showingResults.value = true;

    setTimeout(() => {
        isLoading.value = false;
    }, 1000);

}

const getLabs = () => {
    let labDirector = LabDirector.initialize(rawLabs.value);

    const labCount = labDirector.labResults.length

    if (labCount === 0) {
        return 0;
    }

    labResults.value = labDirector.labResults;
    unparsableRows.value = labDirector.unparsableRows;
    labels.value = labDirector.labels;
    panels.value = labDirector.panels;
    dateTimeHeaders.value = labDirector.dateTimeHeaders;

    return labCount;
}

const closeModal = () => {
    showingResults.value = false;
};

function getLab(results, specimenUniqueId, name) {
    return results.filter((result) => result.specimenUniqueId === specimenUniqueId)
        .find((result) => result.name === name);
}
</script>

<template>
    <div>
        <div v-if="errors.length" class="my-2 text-red-600 font-semibold">
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>
        <label for="input" hidden>Lab Input</label>
        <textarea v-model="rawLabs"
                  :disabled="isLoading"
                  class="font-mono mb-3 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded border border-gray-300
                  focus:ring-sky-500 focus:border-sky-500
                  shadow-2xl shadow-black/10 ring-1 ring-white/5
                  "
                  placeholder="Paste labs here..."
                  rows="12"></textarea>
        <div class="flex justify-between mt-4">
            <button :disabled="isLoading"
                    class="focus:outline-none text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
                    font-medium rounded text-sm px-5 py-2.5 mb-2
                    shadow-2xl shadow-black/10 ring-1 ring-white/5"
                    @click="formatLabs">
                <span v-show="isLoading" role="status">
                    <svg class="mr-3 -ml-1 size-5 animate-spin text-white" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" fill="currentColor"></path>
                    </svg>

                    <span class="sr-only">Loading...</span>
                </span>
                <span v-show="!isLoading">Format</span>
            </button>

            <button class="text-gray-800 bg-gray-300 border border-gray-400 hover:bg-gray-500 hover:text-gray-200
                focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded text-sm px-5 py-2.5 mb-2
                shadow-2xl shadow-black/10 ring-1 ring-white/5" @click="resetForm">
                Clear
            </button>
        </div>
    </div>

    <Modal v-slot="slotProps" :show="showingResults" @close="closeModal">
        <div class="grid gap-6 grid-cols-1 lg:gap-8">
            <table class="text-sm border-collapse border-spacing-0">
                <thead>
                <tr style="font-weight: bolder">
                    <th class="border-b border-gray-500 z-40 sticky bg-gray-200 top-0 left-0" colspan="2"
                        scope="col">
                        <div class="top-0 left-0 flex justify-between py-1 px-2">
                            <button
                                class="modal-close text-xs p2 font-medium text-white bg-sky-700
                                        rounded-lg hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300"
                                @click="slotProps.close()">
                                <svg class="fill-current" height="16" viewBox="0 0 18 18"
                                     width="16"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                                </svg>
                            </button>
                            <div class="mr-2 relative inline-block border-b border-gray-300 border-dotted group">
                                <svg class="bi bi-info-circle" fill="currentColor" height="16" viewBox="0 0 16 16"
                                     width="16" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path
                                        d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                </svg>
                                <div
                                    class="font-medium text-sm l-1/2 top-[135%]-ml-24 w-48 border border-gray-500 bg-gray-200 text-gray-800 rounded p-2 invisible opacity-0 group-hover:visible z-10 group-hover:opacity-100 absolute transition-opacity after:content-[''] after:absolute after:bottom-full after:left-1/2 after:-ml-1 after:border-1 after:border-solid after:border-t-transparent after:border-r-transparent after:border-b-gray-500 after:border-l-transparent">
                                    <h5 class="font-bold">Navigation</h5>
                                    <p>Press <kbd
                                        class="bg-gray-800 text-gray-100 rounded font-mono py-0.5 px-1">ESC</kbd>
                                       to exit<br/>
                                       Hold <kbd
                                            class="bg-gray-800 text-gray-100 rounded font-mono py-0.5 px-1">Shift</kbd>
                                       to scroll horizontally
                                    </p>

                                </div>
                            </div>
                        </div>
                    </th>
                    <th v-for="specimen in dateTimeHeaders.value" class="dateHeader"
                        scope="col"
                        v-html="specimen.collectionDate.toFormat('L/d/yy<br />H:mm')"></th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(label, key, index) in labels.value">
                    <tr class="resultRow group">
                        <th v-if="labels.value[Object.keys(labels.value)[index]]?.panel!==labels.value[Object.keys(labels.value)[index-1]]?.panel"
                            :rowspan="panels.value[label.panel]" class="panelHeader"
                            style="writing-mode: vertical-lr;">{{ label.panel }}
                        </th>
                        <th class="labelHeader" scope="row">{{ label.label }}</th>
                        <ResultCell v-for="specimen in dateTimeHeaders.value"
                                    :flag="getLab(labResults.value, specimen.specimenUniqueId, label.name)?.flag"
                                    :result="getLab(labResults.value, specimen.specimenUniqueId, label.name)?.result"
                        />
                    </tr>
                </template>
                </tbody>
            </table>

            <Unparsable :rows="unparsableRows.value"/>
        </div>
    </Modal>
</template>

