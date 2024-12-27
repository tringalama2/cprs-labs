<script setup>

import {dateTimeHeaders, labels, labResults, panels, unparsableRows} from "@/LabsDisplay/index.js";
import ResultCell from "@/Components/ResultCell.vue";
import {onMounted} from "vue";

function getLab(specimenUniqueId, name) {
    return labResults.filter((result) => result.specimenUniqueId === specimenUniqueId)
        .find((result) => result.name === name);
}

onMounted(function () {
    //console.log(labResults);
    //console.log(unparsableRows);
})
</script>

<template>
    <div class="grid gap-6 grid-cols-1 lg:gap-8">
        <table class="text-sm border-collapse border-spacing-0">
            <thead>
            <tr style="font-weight: bolder">
                <th class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0" scope="col"></th>
                <th class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0" scope="col"></th>
                <th v-for="specimen in dateTimeHeaders" class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0"
                    scope="col"
                    v-html="specimen.collectionDate.toFormat('L/d/yy<br />H:mm')"></th>
            </tr>
            </thead>
            <tbody>
            <template v-for="(label, key, index) in labels">
                <tr class="border-b border-gray-500 hover:bg-sky-200 group">
                    <th v-if="labels[Object.keys(labels)[index]]?.panel!==labels[Object.keys(labels)[index-1]]?.panel"
                        :rowspan="panels[label.panel]" class="font-extrabold border-r border-gray-500 group-hover:bg-white text-xl text-start ps-3 z-30 sticky bg-white left-0"
                        style="writing-mode: vertical-lr;">{{ label.panel }}
                    </th>
                    <th class="border-r border-gray-500 px-2 bg-gray-200 group-hover:bg-sky-300 z-20 sticky left-7" scope="row"
                    >{{ label.label }}
                    </th>
                    <ResultCell v-for="specimen in dateTimeHeaders"
                                :flag="getLab(specimen.specimenUniqueId, label.name)?.flag"
                                :result="getLab(specimen.specimenUniqueId, label.name)?.result"
                    />
                </tr>
            </template>
            </tbody>
        </table>
        <div v-if="unparsableRows.length > 0" class="bg-white">
            <h2 class="text-lg underline">Unable To Process</h2>
            <div v-for="row in unparsableRows">
                {{ row.result }}
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
