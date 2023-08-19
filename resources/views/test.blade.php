@php use Carbon\Carbon; @endphp
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
            @foreach($labLabelsSorted as $lab)
                <tr class="border-b border-gray-500">
                    <th scope="row" class="border-r border-gray-500 px-2">{{ $lab }}</th>
                    @foreach($labs->pluck('collection_date')->unique() as $date)
                        <td class="border-r border-gray-500 px-2 text-center">{{ $labs->where('collection_date', $date)->where('name', $lab)->first()?->get('result') }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        <h2 class="text-lg underline">Unable To Process</h2>
        @foreach($unableToParse as $row)
            <div>{{ $row }}</div>
        @endforeach
    </div>
</x-app-layout>
