<script setup>
import Version from "@/Components/Version.vue";
</script>

<template>
    <div
        class="relative min-h-screen bg-gray-100 bg-center pt-16 pb-8 md:pt-20 md:pb-10 selection:bg-sky-500 selection:text-white">

        <slot/>

        <div class="mx-auto mt-8">
            <footer class="p-4 border-t-2 border-gray-200 md:flex md:items-center md:justify-between md:p-6">
                <Version/>
                <ul class="flex flex-wrap items-center mt-3 sm:mt-0">
                    <li>
                        <a class="mr-8 text-xs text-gray-400 hover:underline md:mr-12" href="https://www.buymeacoffee.com/tringali"
                           target="_blank">Buy me a burrito bowl &#127791;
                                           &#129379; </a>
                    </li>
                    <li>
                        <a class="mr-4 text-xs text-gray-400 hover:underline md:mr-6" href="/terms">Terms</a>
                    </li>
                    <li>
                        <a class="mr-4 text-xs text-gray-400 hover:underline md:mr-6" href="/policy">Privacy
                                                                                                     Policy</a>
                    </li>
                </ul>
            </footer>
        </div>
    </div>
</template>
