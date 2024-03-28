@extends('layouts.app')

@section('content')
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
            <tbody class="divide-y divide-gray-200">
                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ __('Token') }}</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ __('Price') }}</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ __('Change') }}</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>

                @foreach($priceTokens as $token => $data)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">
                            <img width="16px" height="16px" src="https://app.osmosis.zone/tokens/generated/{{ strtolower($token) }}.svg" />
                            {{ $token }}</td>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">${{ $data['price'] }}</td>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $data['change'] }}</td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="{{ route('home.token', [$token]) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                {{ __('View') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
