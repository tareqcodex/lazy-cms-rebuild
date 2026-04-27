@php
    $s = $el['settings'] ?? [];
@endphp
<div class="element-heading mb-4">
    <h2 style="text-align: {{ $s['textAlign'] ?? 'left' }}; margin: 0; padding: 0;" class="text-slate-800 font-bold leading-tight">
        {{ $s['title'] ?? 'New Heading' }}
    </h2>
</div>
