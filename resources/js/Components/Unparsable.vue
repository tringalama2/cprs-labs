<script setup>
const props = defineProps({
    rows: {
        type: Array,
        default: [],
    },
});
</script>
<template>
    <div v-if="rows?.length > 0" class="bg-white my-3 mx-8 rounded-xl border bg-card text-card-foreground shadow">
        <div class="flex flex-col space-y-1.5 p-6">
            <h2 class="font-semibold leading-none tracking-tight">
                Hmm... this bit of data dodged our formatting efforts like a ninja.
            </h2>
            <h2 class="text-sm text-muted-foreground">
                You might need to wrestle with it manually.
            </h2>
        </div>
        <div class="p-6 pt-0">
            <div v-for="row in rows"
                 class="mb-4 grid grid-cols-[25px_1fr] items-start pb-4 last:mb-0 last:pb-0">
                <span class="flex h-2 w-2 translate-y-1 rounded-full bg-sky-600"/>
                <div class="space-y-1">
                    <p class="text-sky-950 text-sm font-medium leading-none">
                        {{ row.result }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</template>
