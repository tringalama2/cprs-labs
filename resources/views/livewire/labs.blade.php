<div>
    <form wire:submit.prevent.defer="save">
        <textarea wire:model="input"
                  rows="8"
                  class="mb-3 block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded border border-gray-300
                  focus:ring-sky-500 focus:border-sky-500"
                  placeholder="Paste labs here..."></textarea>
        <div class="flex justify-between">
            <button class="focus:outline-none text-white bg-sky-600 hover:bg-sky-700 focus:ring-4 focus:ring-sky-300
                    font-medium rounded text-sm px-5 py-2.5 mb-2">
                Format
            </button>

            <button wire:click="clear" class="text-gray-800 bg-gray-300 border border-gray-400 hover:bg-gray-500 hover:text-gray-200
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

