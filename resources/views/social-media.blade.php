@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-4">Social Media Analytics</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($socialData as $platform => $data)
                    <div class="bg-gray-50 p-6 rounded-lg shadow">
                        <div class="flex items-center mb-4">
                            <div class="w-8 h-8 rounded-full mr-3" style="background-color: {{ $data['config']['color'] ?? '#000000' }}"></div>
                            <h3 class="text-xl font-semibold">{{ ucwords(str_replace('_', ' ', $platform)) }}</h3>
                        </div>

                        <div class="space-y-4">
                            @foreach($data['metrics']['metrics'] as $metric => $value)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $metric)) }}</span>
                                <span class="font-semibold">{{ is_numeric($value) ? number_format($value) : $value }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="mt-4 text-sm text-gray-500">
                            Last updated: {{ $data['metrics']['date'] }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
