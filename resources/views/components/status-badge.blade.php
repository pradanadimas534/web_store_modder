@props(['status', 'label' => null])

@php
    $value = is_object($status) && property_exists($status, 'value') ? $status->value : (string) $status;
    $text = $label ?? (is_object($status) && method_exists($status, 'label') ? $status->label() : ucfirst(str_replace('_', ' ', $value)));

    $classes = match ($value) {
        'active', 'completed', 'free', 'verified' => 'border-[#22C55E]/40 bg-[#22C55E]/15 text-[#22C55E]',
        'pending', 'pending_review', 'pending_payment', 'pending_verification', 'paid', 'unpaid' => 'border-[#FBBF24]/40 bg-[#FBBF24]/15 text-[#FBBF24]',
        'rejected', 'cancelled', 'disabled' => 'border-[#F87171]/40 bg-[#F87171]/15 text-[#F87171]',
        default => 'border-[#30364D] bg-[#1A1E30] text-[#AAB2C5]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-semibold {$classes}"]) }}>
    {{ $text }}
</span>
