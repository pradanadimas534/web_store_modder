@props(['status', 'label' => null])

@php
    $value = is_object($status) && property_exists($status, 'value') ? $status->value : (string) $status;
    $text = $label ?? (is_object($status) && method_exists($status, 'label') ? $status->label() : ucfirst(str_replace('_', ' ', $value)));

    $classes = match ($value) {
        'active', 'completed', 'free', 'verified' => 'border-[#35D07F]/40 bg-[#35D07F]/15 text-[#35D07F]',
        'pending', 'pending_review', 'pending_payment', 'pending_verification', 'paid', 'unpaid' => 'border-[#F5C451]/40 bg-[#F5C451]/15 text-[#F5C451]',
        'rejected', 'cancelled', 'disabled' => 'border-[#FF6262]/40 bg-[#FF6262]/15 text-[#FF6262]',
        default => 'border-[#29342D] bg-[#151D19] text-[#A0AAA2]',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-lg border px-2.5 py-1 text-xs font-semibold {$classes}"]) }}>
    {{ $text }}
</span>
