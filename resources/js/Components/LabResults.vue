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

    setTimeout(() => {
        showingResults.value = true;
        isLoading.value = false;
    }, 1);
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
                    <svg aria-hidden="true"
                         class="inline w-4 h-4 mx-2 text-gray-200 animate-spin fill-sky-600"
                         fill="none" viewBox="0 0 100 101" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill"/>
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

    <Modal :show="showingResults" @close="closeModal">
        <div class="grid gap-6 grid-cols-1 lg:gap-8">
            <table class="text-sm border-collapse border-spacing-0">
                <thead>
                <tr style="font-weight: bolder">
                    <th class="dateHeader" scope="col"></th>
                    <th class="dateHeader" scope="col"></th>
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
                        <th class="labelHeader" scope="row"
                        >{{ label.label }}
                        </th>
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

