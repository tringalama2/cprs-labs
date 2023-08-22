<x-app-layout>
    <div>


        <table class="text-sm">
            <thead>
            <tr>
                <th scope="col" class="border-b border-gray-500"></th>
                <th scope="col"
                    class="border-b border-gray-500 px-2">{!! $datetimeHeaders->implode('</th><th class="border-b border-gray-500  px-2">') !!}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $loopPanel = '';
            @endphp
            @foreach($labLabelsSorted as $labLabel)
                <tr class="border-b border-gray-500">
                    @if ($labLabel->panel != $loopPanel)
                        <th rowspan="{{ $panels[$labLabel->panel] }}"
                            class="rotate-[-90deg] font-extrabold border-r border-gray-500">{{ $labLabel->panel }}</th>
                    @endif
                    <th scope="row" class="border-r border-gray-500 px-2">{{ $labLabel->label }}</th>
                    @foreach($labs->groupBy('collection_date') as $date)
                        @php
                            $lab = $date->where('name', $labLabel->name)->first();
                        @endphp

                        <td class="border-r border-gray-500 px-2 text-center whitespace-nowrap
                        @if(str($lab?->get('flag'))->contains('*'))
                        bg-red-500
                        @elseif(str($lab?->get('flag'))->contains(['H', 'L']))
                        bg-red-300
                        @endif
                        ">{{ $lab?->get('result') }}
                        </td>
                    @endforeach
                </tr>
                @php
                    $loopPanel = $labLabel->panel
                @endphp
            @endforeach
            </tbody>
        </table>
        <h2 class="text-lg underline">Unable To Process</h2>
        @foreach($unparsableRows as $row)
            <div>{{ implode(' ', $row->toArray()) }}</div>
        @endforeach
    </div>
</x-app-layout>
