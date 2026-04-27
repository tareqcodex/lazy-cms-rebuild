@php
    $s = $el['settings'] ?? [];
@endphp
<div class="element-text mb-4">
    <div class="prose prose-slate max-w-none">
        {!! $s['content'] ?? 'Start typing your content here...' !!}
    </div>
</div>
