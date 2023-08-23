<x-app-layout>
    <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
            CPRS Lab Formatter
        </div>

        <div class="mt-16">
            <livewire:labs/>
        </div>

        <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
            <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                <div class="flex items-center gap-4">
                    Buy me a coffee
                </div>
            </div>

            <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
                Last Updated {{ (new DateTime(trim(exec('git log -n1 --pretty=%ci HEAD'))))->format('M j, Y') }}
            </div>
        </div>
    </div>
</x-app-layout>

