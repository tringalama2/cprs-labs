@php use Carbon\Carbon; @endphp
<div>
    <form wire:submit="save">
        <label hidden for="input">Lab Input</label>
        <textarea id="input"
                  wire:model="input"
                  wire:loading.attr="disabled"
                  rows="20"
                  class="font-mono mb-3 block p-2.5 w-full text-sm text-gray-600 bg-gray-50 rounded border border-gray-300
                  focus:ring-sky-500 focus:border-sky-500
                  shadow-2xl shadow-black/10 ring-1 ring-white/5
                  "
                  placeholder="Paste labs here..."></textarea>
        <x-input-error :messages="$errors->get('input')" class="mt-2"/>
        <div class="flex justify-between mt-4">
            <button class="focus:outline-none text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
                    font-medium rounded text-xl px-7 py-4 mb-2
                    shadow-2xl shadow-black/10 ring-1 ring-white/5"
                    wire:loading.attr="disabled">
                <div role="status" wire:loading wire:target="save">
                    <svg aria-hidden="true"
                         class="inline w-4 h-4 mx-2 text-gray-200 animate-spin fill-sky-600"
                         viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor"/>
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill"/>
                    </svg>
                    <span class="sr-only">Loading...</span>
                </div>
                <span wire:loading.remove wire:target="save">Format</span>
            </button>

            <button wire:click.prevent="clear" class="text-gray-800 bg-gray-300 border hover:bg-gray-500 hover:text-gray-100
                focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded text-sm px-5 py-2.5 mb-2
                shadow-2xl shadow-black/10 ring-1 ring-white/5">
                Clear
            </button>
        </div>
    </form>


    <!--Modal-->
    <div
        x-cloak
        x-data="{ showModal: false }"
        x-show="showModal"
        @keydown.escape.window="showModal = false"
        x-on:results-ready.window="showModal = true"
        x-transition.opacity
        class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-white opacity-100"></div>

        <div class="modal-container fixed w-full h-full z-50 overflow-y-auto ">


            <!-- Add margin if you want to see grey behind the modal-->
            <div class="modal-content container h-auto text-left">

                <table class="text-sm border-collapse border-spacing-0">
                    <thead>
                    <tr>
                        <th scope="col" class="border-b border-gray-500 z-40 sticky bg-gray-200 top-0 left-0"
                            colspan="2">
                            <div class="top-0 left-0 flex justify-between py-1 px-2">
                                <button
                                    @click="showModal = false"
                                    class="modal-close text-xs p2 font-medium text-white bg-sky-700
                                        rounded-lg hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300">
                                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="16"
                                         height="16"
                                         viewBox="0 0 18 18">
                                        <path
                                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                                    </svg>
                                </button>

                                <div class="mr-2 relative inline-block border-b border-gray-300 border-dotted group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-info-circle" viewBox="0 0 16 16">
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
                        <th scope="col"
                            class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0"
                        >{!! $datetimeHeaders?->implode('</th><th class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">') !!}</th>
                    </tr>
                    </thead>
                    @if($labs)
                        <tbody>
                        @php($loopPanel = '')
                        @foreach($labLabelsSorted as $labLabel)
                            <tr class="border-b border-gray-500 hover:bg-sky-200 group">
                                @if ($labLabel['panel'] != $loopPanel)
                                    <th rowspan="{{ $panels[$labLabel['panel']] }}"
                                        class="font-extrabold border-r border-gray-500 group-hover:bg-white text-xl text-start ps-3 z-30 sticky bg-white left-0"
                                        style="writing-mode: vertical-lr;">{{ $labLabel['panel'] }}</th>
                                @endif
                                <th scope="row" class="border-r border-gray-500 px-2 bg-gray-200 group-hover:bg-sky-300
                         z-20 sticky left-7
                        ">{{ $labLabel['label'] }}</th>
                                @foreach($labs->groupBy('specimen_unique_id') as $specimen)
                                    @php($lab = $specimen->where('name', $labLabel['name'])->first())
                                    <td class="border-r border-gray-500 px-2 text-center whitespace-nowrap
                                        @if(str($lab?->get('flag'))->contains('*'))
                                        bg-red-500 text-red-950 group-hover:bg-sky-500 font-bold
                                        @elseif(str($lab?->get('flag'))->contains(['H', 'L']))
                                        bg-red-300 text-red-900 group-hover:bg-sky-400 font-bold
                                        @else
                                        group-hover:bg-sky-200 bg-white
                                        @endif
                                        ">{{ $lab?->get('result') }}
                                    </td>
                                @endforeach
                            </tr>
                        @php($loopPanel = $labLabel['panel'])
                        @endforeach
                    @endif
                </table>

                @if($calculatedValues && count($calculatedValues) > 0)
                    <div class="mt-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 px-2 py-2 bg-gradient-to-r from-blue-100 to-blue-200 border-l-4 border-blue-500">
                            Calculated Values
                        </h2>
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($calculatedValues as $result)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $result['display_name'] }}</h3>
                                        <div class="text-2xl font-bold text-blue-600 mb-2">{{ $result['display_value'] }}</div>
                                        <div class="text-sm text-gray-600 mb-3">
                                            <span class="font-medium">Interpretation:</span>
                                            <span class="@if(str_contains(strtolower($result['interpretation']), 'high') || str_contains(strtolower($result['interpretation']), 'low') || str_contains(strtolower($result['interpretation']), 'abnormal')) text-red-600 font-medium @else text-green-600 @endif">
                                                {{ $result['interpretation'] }}
                                            </span>
                                        </div>
                                        <details class="text-sm">
                                            <summary class="cursor-pointer text-gray-500 hover:text-gray-700 font-medium">
                                                Details
                                            </summary>
                                            <div class="mt-2 space-y-2">
                                                <div>
                                                    <span class="font-medium text-gray-700">Formula:</span>
                                                    <code class="text-xs bg-gray-100 px-1 py-0.5 rounded">{{ $result['formula'] }}</code>
                                                </div>
                                                <div>
                                                    <span class="font-medium text-gray-700">Values used:</span>
                                                    <ul class="text-xs text-gray-600 mt-1">
                                                        @foreach($result['used_values'] as $name => $value)
                                                            <li>
                                                                {{ $name }}: {{ $value }}
                                                                @if(isset($result['used_value_dates'][$name]))
                                                                    <span class="text-gray-500">
                                                                        ({{ Carbon::parse($result['used_value_dates'][$name])->format('M j, Y g:i A') }})
                                                                    </span>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <table class="text-sm border-collapse border-spacing-0">
                    <thead>
                    <tr>
                        <th scope="col" class="border-b border-gray-500 z-40 sticky bg-gray-200 top-0 left-0" colspan="2"></th>
                        <th scope="col" class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">
                            {!! $microDateTimeHeaders?->implode('</th><th class="text-center border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">') !!}
                        </th>
                    </tr>
                    </thead>
                    @if($micro)
                        <tbody>
                        @php($loopPanel = '')
                        @foreach($microLabels as $labLabel)
                            <tr class="border-b border-gray-500 hover:bg-sky-200 group/row">
                                @if ($labLabel['panel'] != $loopPanel)
                                    <th rowspan="{{ $micro->groupBy('name')->count() }}"
                                        class="font-extrabold border-r border-gray-500 group-hover/row:bg-white text-xl text-start ps-3 z-30 sticky bg-white left-0"
                                        style="writing-mode: vertical-lr;">{{ $labLabel['panel'] }}</th>
                                @endif
                                <th scope="row" class="border-r border-gray-500 px-2 bg-gray-200 group-hover/row:bg-sky-300
                         z-20 sticky left-7
                        ">{{ $labLabel['label'] }}</th>
                                @foreach($micro->groupBy('accession_unique_id') as $accession)
                                    @php($lab = $accession->where('name', $labLabel['name'])->first())
                                    <td class="border-r border-gray-500 px-2 text-center whitespace-nowrap group/result group-hover/row:bg-sky-200 bg-white">
                                        @if($lab)
                                            Sample: {{ $lab->get('sample') }}
                                            @if($lab->get('specimen'))
                                            , {{ $lab->get('specimen') }}
                                            @endif
                                            <div
                                                class="font-medium text-sm l-1/2 top-[135%]-ml-24 border border-gray-500 bg-gray-200 text-gray-800 rounded p-2 invisible opacity-0 group-hover/result:visible z-10 group-hover/result:opacity-100 absolute transition-opacity after:content-[''] after:absolute after:bottom-full after:left-1/2 after:-ml-1 after:border-1 after:border-solid after:border-t-transparent after:border-r-transparent after:border-b-gray-500 after:border-l-transparent">
                                                <h5 class="font-bold">Result</h5>
                                                <pre>{{ $lab->get('result') }}</pre>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @php($loopPanel = $labLabel['panel'])
                        @endforeach
                    @endif
                </table>

                @if($labs)
                    <div class="bg-white">
                        <h2 class="text-lg underline">Unable To Process</h2>
                        @foreach($unparsableRows as $row)
                            <div>{{ implode(' ', $row->toArray()) }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

