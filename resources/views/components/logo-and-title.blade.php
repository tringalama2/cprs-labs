@props(['title', 'subtitle' => null, 'title_after' => true])
<div class="flex justify-center mb-6 md:mb-12">
    <div class="flex flex-col items-center gap-4">
        <div class="flex-shrink-0">
            <a href="{{ route('home') }}">
                <x-application-logo class="w-12 h-12 block fill-current text-sky-600"/>
            </a>
        </div>
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-2">
                @if(! $title_after)
                    {{ $title }}
                @endif
                <span class="text-sky-600 text-3xl">Easy</span><span class="text-gray-600 text-3xl">CPRSLabs</span>
                @if($title_after)
                    {{ $title }}
                @endif
            </h1>
            @isset($subtitle)
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    {{ $subtitle }}
                </p>
            @endisset
        </div>
    </div>
</div>
