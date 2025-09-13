@props(['type' => 'default', 'text'])

@php
$classes = [
    'default' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'danger' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800',
    'pending' => 'bg-yellow-100 text-yellow-800',
    'active' => 'bg-green-100 text-green-800',
    'inactive' => 'bg-gray-100 text-gray-800',
    'blocked' => 'bg-red-100 text-red-800',
    'approved' => 'bg-green-100 text-green-800',
    'rejected' => 'bg-red-100 text-red-800',
    'paid' => 'bg-green-100 text-green-800',
    'failed' => 'bg-red-100 text-red-800',
    'refunded' => 'bg-blue-100 text-blue-800',
][$type] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $classes }}">
    {{ $text }}
</span>
