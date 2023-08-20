<x-app-layout>
    <div>


        <table>
            <thead>
            <tr>
                <th scope="col" class="border-b border-gray-500"></th>
                <th scope="col"
                    class="border-b border-gray-500 px-2">{!! $datetimeHeader->implode('</th><th class="border-b border-gray-500  px-2">') !!}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($labLabelsSorted as $labNamd)
                <tr class="border-b border-gray-500">
                    <th scope="row" class="border-r border-gray-500 px-2">{{ $labNamd }}</th>
                    @foreach($labs->pluck('collection_date')->unique() as $date)
                        @php
                            $lab = $labs->where('collection_date', $date)->where('name', $labNamd)->first();
                        @endphp
                        <td class="border-r border-gray-500 px-2 text-center
                        @if(str($lab?->get('flag'))->contains('*'))
                        bg-red-500
                        @elseif(str($lab?->get('flag'))->contains(['H', 'L']))
                        bg-red-300
                        @endif
                        ">{{ $lab?->get('result') }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        <h2 class="text-lg underline">Unable To Process</h2>
        @foreach($unparsableRows as $row)
            <div>{{ implode(' ', $row->toArray()) }}</div>
        @endforeach
    </div>
</x-app-layout>
