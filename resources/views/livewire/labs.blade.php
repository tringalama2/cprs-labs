<div>
    <form wire:submit.prevent="save">
        <textarea wire:model.defer="input"
                  wire:loading.attr="disabled"
                  rows="8"
                  class="mb-3 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded border border-gray-300
                  focus:ring-sky-500 focus:border-sky-500"
                  placeholder="Paste labs here..."></textarea>
        <x-input-error :messages="$errors->get('input')" class="mt-2"/>
        <div class="flex justify-between mt-4">
            <button class="focus:outline-none text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
                    font-medium rounded text-sm px-5 py-2.5 mb-2"
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

            <button wire:click.prevent="clear" class="text-gray-800 bg-gray-300 border border-gray-400 hover:bg-gray-500 hover:text-gray-200
                focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded text-sm px-5 py-2.5 mb-2">
                Clear
            </button>
        </div>
    </form>


    <!--Modal-->
    <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
        <div class="modal-overlay absolute w-full h-full bg-white opacity-95"></div>

        <div class="modal-container fixed w-full h-full z-50 overflow-y-auto ">


            <!-- Add margin if you want to see grey behind the modal-->
            <div class="modal-content container h-auto text-left">

                <table class="text-sm border-collapse border-spacing-0">
                    <thead>
                    <tr>
                        <th scope="col" class="border-b border-gray-500 z-40 sticky bg-white top-0 left-0"
                            colspan="2">
                            <div
                                class="absolute top-0 left-0 cursor-pointer flex flex-col items-center mt-2 ms-2
">
                                <button class="modal-close text-xs p2 font-medium text-white bg-sky-700
                rounded-lg hover:bg-sky-800 focus:ring-4 focus:outline-none focus:ring-sky-300">
                                    <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="16"
                                         height="16"
                                         viewBox="0 0 18 18">
                                        <path
                                            d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                                    </svg>
                                </button>
                            </div>
                        </th>
                        <th scope="col"
                            class="border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">{!! $datetimeHeaders?->implode('</th><th class="border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">') !!}</th>
                    </tr>
                    </thead>
                    @if($labs)
                        <tbody>
                        @php
                            $loopPanel = '';
                        @endphp
                        @foreach($labLabelsSorted as $labLabel)
                            <tr class="border-b border-gray-500 hover:bg-sky-200 group">
                                @if ($labLabel->panel != $loopPanel)
                                    <th rowspan="{{ $panels[$labLabel->panel] }}"
                                        class="font-extrabold border-r border-gray-500 group-hover:bg-white text-xl text-start ps-3
                            z-30 sticky bg-white left-0"
                                        style="writing-mode: vertical-lr;">{{ $labLabel->panel }}</th>
                                @endif
                                <th scope="row"
                                    class="border-r border-gray-500 px-2 bg-gray-200 group-hover:bg-sky-300
                         z-20 sticky left-7
                        ">{{ $labLabel->label }}</th>
                                @foreach($labs->groupBy('collection_date') as $date)
                                    @php
                                        $lab = $date->where('name', $labLabel->name)->first();
                                    @endphp

                                    <td class="border-r border-gray-500 px-2 text-center whitespace-nowrap
                        @if(str($lab?->get('flag'))->contains('*'))
                        bg-red-500
                        text-red-950
                        group-hover:bg-sky-500
                        font-bold
                        @elseif(str($lab?->get('flag'))->contains(['H', 'L']))
                        bg-red-300
                        text-red-900
                        group-hover:bg-sky-400
                        font-bold
                        @else
                        group-hover:bg-sky-200
                        bg-white
                        @endif
                        ">{{ $lab?->get('result') }}
                                    </td>
                                @endforeach
                            </tr>
                            @php
                                $loopPanel = $labLabel->panel
                            @endphp
                        @endforeach

                        @foreach($unrecognizedLabLabels as $labLabel)
                            <tr class="border-b border-gray-500 hover:bg-sky-200 group">
                                @if($loop->first)
                                    <th rowspan="{{ $unrecognizedLabLabels->count() }}"
                                        class="font-extrabold border-r border-gray-500 group-hover:bg-white align-top text-start ps-3 text-xl
                            z-30 sticky bg-white left-0"
                                        style="writing-mode: vertical-lr;">Other
                                    </th>
                                @endif
                                <th scope="row"
                                    class="border-r border-gray-500 px-2 bg-gray-200 group-hover:bg-sky-300
                        z-20 sticky left-7">{{ $labLabel }}</th>
                                @foreach($labs->groupBy('collection_date') as $date)
                                    @php
                                        $lab = $date->where('name', $labLabel)->first();
                                    @endphp

                                    <td class="border-r border-gray-500 px-2 text-center whitespace-nowrap
                        @if(str($lab?->get('flag'))->contains('*'))
                        bg-red-500
                        text-red-950
                        group-hover:bg-sky-500
                        font-bold
                        @elseif(str($lab?->get('flag'))->contains(['H', 'L']))
                        bg-red-300
                        text-red-900
                        group-hover:bg-sky-400
                        font-bold
                        @else
                        group-hover:bg-sky-200
                        bg-white
                        @endif
                        ">{{ $lab?->get('result') }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>

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

@push('styles')
    <style>
        .modal {
            transition: opacity 0.25s ease;
        }

        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }

        .opacity-95 {
            opacity: .95;
        }
    </style>
@endpush
@push('endScripts')
    <script>
        Livewire.on('resultsReady', event => {
            toggleModal()
        })
        // var openmodal = document.querySelectorAll('.modal-open')
        // for (var i = 0; i < openmodal.length; i++) {
        //     openmodal[i].addEventListener('click', function (event) {
        //         event.preventDefault()
        //         toggleModal()
        //     })
        // }

        const overlay = document.querySelector('.modal-overlay')
        overlay.addEventListener('click', toggleModal)

        var closemodal = document.querySelectorAll('.modal-close')
        for (var i = 0; i < closemodal.length; i++) {
            closemodal[i].addEventListener('click', toggleModal)
        }

        document.onkeydown = function (evt) {
            evt = evt || window.event
            var isEscape = false
            if ("key" in evt) {
                isEscape = (evt.key === "Escape" || evt.key === "Esc")
            } else {
                isEscape = (evt.keyCode === 27)
            }
            if (isEscape && document.body.classList.contains('modal-active')) {
                toggleModal()
            }
        };

        function toggleModal() {
            const body = document.querySelector('body')
            const modal = document.querySelector('.modal')
            modal.classList.toggle('opacity-0')
            modal.classList.toggle('pointer-events-none')
            body.classList.toggle('modal-active')
        }
    </script>
@endpush

