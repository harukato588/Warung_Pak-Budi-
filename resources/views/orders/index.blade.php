@extends('layouts.app')

@section('content')
{{-- ⚠️ VULN #3: IDOR — semua order semua user ditampilkan, tanpa filter user --}}
<h1 class="text-2xl font-bold mb-6">📦 Semua Order (Seluruh User)</h1>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="text-left p-3">#ID</th>
                <th class="text-left p-3">User ID</th>
                <th class="text-left p-3">Status</th>
                <th class="text-left p-3">Total</th>
                <th class="text-left p-3">Tanggal</th>
                <th class="text-left p-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $order->id }}</td>
                {{-- ⚠️ Info Disclosure: user_id tampil --}}
                <td class="p-3 text-gray-500">User #{{ $order->user_id }}</td>
                <td class="p-3">{{ ucfirst($order->status) }}</td>
                <td class="p-3 font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td class="p-3 text-gray-500">{{ $order->created_at }}</td>
                <td class="p-3">
                    {{-- ⚠️ IDOR: akses order orang lain --}}
                    <a href="/order/{{ $order->id }}"
                       class="text-blue-600 hover:underline text-sm">Lihat Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
