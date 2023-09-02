@props(['style' => session('style', 'info'), 'message' => session('message')])

<div
        x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
        style="display: none;"
        x-show="show && message"
        x-init="setTimeout(() => show = false, 6000);"
        x-on:flash-message.window="
                style = event.detail.style ? event.detail.style : 'info';
                message = event.detail.message;
                show = true;
            "
        :class="{
                'bg-red-500': style == 'danger',
                'bg-yellow-500': style == 'warning',
                'bg-green-600': style == 'success',
                'bg-blue-500': style == 'info'
            }"
        class="absolute right-5 bottom-5 z-50 rounded-sm flex items-center text-sm font-bold px-3 py-2 text-white"
        role="alert">
    <p x-text="message" class="px-2"></p>
    <span class="relative top-0 bottom-0 right-0">
            <svg role="button" xmlns="http://www.w3.org/2000/svg"
                 :class="{
                        'fill-red-300': style == 'danger',
                        'fill-yellow-300': style == 'warning',
                        'fill-green-400': style == 'success',
                        'fill-blue-300': style == 'info'
                    }"
                 class="w-5 h-5"
                 viewBox="0 0 20 20"
                 @click="show = false"><title>Close</title><path
                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
    </span>
</div>
