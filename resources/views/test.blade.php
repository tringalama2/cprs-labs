<x-guest-layout>
    <div>
        <table class="text-sm border-collapse border-spacing-0">
            <thead>
            <tr>
                <th scope="col" class="border-b border-gray-500 z-40 sticky bg-white top-0 left-0"></th>
                <th scope="col" class="border-b border-gray-500 z-40 sticky bg-white top-0 left-0"></th>
                <th scope="col"
                    class="border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">{!! $datetimeHeaders->implode('</th><th class="border-b border-gray-500 px-2 z-20 sticky bg-gray-200 top-0">') !!}</th>
            </tr>
            </thead>
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
        </table>
        <div class="bg-white">
            <h2 class="text-lg underline">Unable To Process</h2>
            @foreach($unparsableRows as $row)
                <div>{{ implode(' ', $row->toArray()) }}</div>
            @endforeach
        </div>
    </div>
</x-guest-layout>
