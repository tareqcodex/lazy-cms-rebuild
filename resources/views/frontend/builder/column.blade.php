@php
    $s = $column['settings'] ?? [];
    $columnStyles = [
        'flex-basis: ' . ($column['basis'] ?? 100) . '%',
        'max-width: ' . ($column['basis'] ?? 100) . '%',
    ];

    if (isset($s['paddingTop'])) $columnStyles[] = "padding-top: {$s['paddingTop']}px";
    if (isset($s['paddingBottom'])) $columnStyles[] = "padding-bottom: {$s['paddingBottom']}px";
    if (isset($s['paddingLeft'])) $columnStyles[] = "padding-left: {$s['paddingLeft']}px";
    if (isset($s['paddingRight'])) $columnStyles[] = "padding-right: {$s['paddingRight']}px";
    
    if (isset($s['marginTop'])) $columnStyles[] = "margin-top: {$s['marginTop']}px";
    if (isset($s['marginBottom'])) $columnStyles[] = "margin-bottom: {$s['marginBottom']}px";
    if (isset($s['marginLeft'])) $columnStyles[] = "margin-left: {$s['marginLeft']}px";
    if (isset($s['marginRight'])) $columnStyles[] = "margin-right: {$s['marginRight']}px";

    $innerStyles = [
        'display: flex',
        'flex-direction: ' . ($s['contentLayout'] ?? 'column'),
        'justify-content: ' . ($s['contentAlignment'] ?? 'flex-start'),
        'align-items: ' . ($s['alignItems'] ?? 'stretch'),
    ];

    $htmlTag = $s['htmlTag'] ?? 'div';
@endphp

<{{ $htmlTag }} class="lazy-column {{ $s['cssClass'] ?? '' }}" style="{{ implode('; ', $columnStyles) }}">
    <div class="lazy-column-inner h-full" style="{{ implode('; ', $innerStyles) }}">
        @if(!empty($column['elements']))
            @foreach($column['elements'] as $el)
                @switch($el['type'])
                    @case('heading')
                        @include('cms-dashboard::frontend.builder.elements.heading', ['el' => $el])
                        @break
                    @case('text')
                        @include('cms-dashboard::frontend.builder.elements.text', ['el' => $el])
                        @break
                    @case('row')
                        @include('cms-dashboard::frontend.builder.render', ['layout' => [$el]])
                        @break
                @endswitch
            @endforeach
        @endif
    </div>
</{{ $htmlTag }}>
