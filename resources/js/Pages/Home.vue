<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import Layout from '@/Layouts/Layout.vue';
import Instructions from '@/Components/Instructions.vue';
import LabResults from "@/Components/LabResults.vue";
</script>

<template>
    <Layout>
        <Head title="Lab Formatter"></Head>


        <div class="p-4 md:p-6 mx-auto max-w-5xl lg:max-w-7xl">
            <div class="flex justify-center mb-6 md:mb-12">
                <h1 class="text-4xl tracking-tight">CPRS Lab Formatter</h1>
            </div>
            <div class="mt-4 flex flex-col md:flex-row  gap-12 lg:gap-20 xl:gap-24 2xl:gap-28">
                <div class="md:basis-1/3 lg:basis-1/4 2xl:basis-1/5">
                    <div class=" p-8 rounded bg-white shadow-2xl shadow-black/10 ring-1 ring-white/5">
                        <!-- Instruction Steps Panel -->
                        <Instructions/>
                    </div>
                </div>
                <div class="md:basis-2/3 lg:basis-1/2 2xl:basis-3/5">

                    <div>
                        <LabResults/>
                    </div>
                    <div class="flex justify-center px-0 mt-16 sm:items-center sm:justify-between">
                        <div class="text-sm text-center text-gray-500 sm:text-left">
                            <p class="mb-4">
                                *Disclaimer: This site is designed to provide a convenient method to view labs in a
                                tabular format and grouped by standard panels. It may not display every lab and
                                does not contain all the comments for each lab. Users should verify that
                                all tests ordered are accounted for. The data and calculations
                                provided do not substitute for the healthcare providers
                                own chart review or their clinical judgment.
                            </p>
                            <p class="mb-4">
                                Do not transmit patient identifiers. Laboratory data is not saved on servers,
                                however it is transmitted securely to be formatted and then
                                returned to the end user.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </Layout>
</template>

