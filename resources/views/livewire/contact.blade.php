<div class="bg-white rounded shadow-2xl shadow-black/10 ring-1 ring-white/5 p-8 md:p-12">
    @if($showSuccess)
        <div x-data="{ show: true }"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="mb-8 bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-green-800">Message Sent Successfully!</h3>
                    <p class="text-green-700 text-sm mt-1">Thank you for contacting us. We'll get back to you soon.</p>
                </div>
                <div class="ml-auto">
                    <button @click="show = false" class="text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @error('general')
    <div class="mb-8 bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-red-800 font-medium">{{ $message }}</p>
            </div>
        </div>
    </div>
    @enderror

    <form wire:submit="submit" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    wire:model.live.debounce.300ms="name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 transition-colors duration-200 @error('name')  focus:ring-red-500 @enderror"
                    placeholder="Your full name"
                    maxlength="100"
                >
                @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    wire:model.live.debounce.300ms="email"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 transition-colors duration-200 @error('email') focus:ring-red-500 @enderror"
                    placeholder="your.email@example.com"
                    maxlength="255"
                >
                @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Subject Field -->
        <div>
            <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                Subject <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="subject"
                wire:model.live.debounce.300ms="subject"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 transition-colors duration-200 @error('subject') focus:ring-red-500 focus:border-red-500 @enderror"
                placeholder="What's this about?"
                maxlength="200"
            >
            @error('subject')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Message Field -->
        <div>
            <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                Message <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <textarea
                    id="message"
                    wire:model.live.debounce.500ms="message"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 transition-colors duration-200 resize-none @error('message') focus:ring-red-500 focus:border-red-500 @enderror"
                    placeholder="Tell us what's on your mind..."
                    maxlength="1000"
                ></textarea>
                <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                    <span wire:ignore>{{ strlen($message) }}</span>/1000
                </div>
            </div>
            @error('message')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center pt-4">
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="inline-flex items-center px-8 py-4 bg-sky-600 hover:bg-sky-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2"
            >
                <span wire:loading.remove wire:target="submit">
                    <x-icons.send fill="currentColor" class="mr-2 w-5"/>
                    Send Message
                </span>
                <span wire:loading wire:target="submit" class="flex items-center">
                    <svg class="inline animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </button>
        </div>
    </form>


</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('contact-sent', () => {
            // Scroll to top to show success message
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
</script>
