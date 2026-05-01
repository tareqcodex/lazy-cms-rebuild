@php
    $s     = $column['settings'] ?? [];
    $basis = floatval($column['basis'] ?? 100);

    $flexBasis = "{$basis}%";
    $outerStyles = [
        "flex: 0 0 {$flexBasis}",
        "max-width: {$flexBasis}",
    ];

    if (!empty($s['alignment']) && $s['alignment'] !== 'default') {
        $outerStyles[] = 'align-self: ' . $s['alignment'];
    }
    if (intval($s['marginTop']    ?? 0) !== 0) $outerStyles[] = 'margin-top: '    . intval($s['marginTop'])    . 'px';
    if (intval($s['marginBottom'] ?? 0) !== 0) $outerStyles[] = 'margin-bottom: ' . intval($s['marginBottom']) . 'px';

    // Inner: padding, layout, typography, background, border, shadow
    $innerStyles = [
        'height: 100%',
        'padding-top: '    . intval($s['paddingTop']    ?? 10) . 'px',
        'padding-bottom: ' . intval($s['paddingBottom'] ?? 10) . 'px',
        'padding-left: '   . intval($s['paddingLeft']   ?? 10) . 'px',
        'padding-right: '  . intval($s['paddingRight']  ?? 10) . 'px',
        'box-sizing: border-box',
    ];
    if (intval($s['marginLeft']  ?? 0) !== 0) $innerStyles[] = 'margin-left: '  . intval($s['marginLeft'])  . 'px';
    if (intval($s['marginRight'] ?? 0) !== 0) $innerStyles[] = 'margin-right: ' . intval($s['marginRight']) . 'px';

    $contentLayout = $s['contentLayout'] ?? '';
    if ($contentLayout && $contentLayout !== 'block') {
        $innerStyles[] = 'display: flex';
        $innerStyles[] = 'flex-direction: ' . ($contentLayout === 'row' ? 'row' : 'column');
        $gw = intval($s['gapWidth']  ?? 0);
        $gh = intval($s['gapHeight'] ?? 0);
        if ($gw > 0 || $gh > 0) $innerStyles[] = 'gap: ' . $gh . 'px ' . $gw . 'px';
        if ($contentLayout === 'row') {
            if (!empty($s['contentAlignH'])) $innerStyles[] = 'justify-content: ' . $s['contentAlignH'];
            if (!empty($s['contentAlignV'])) $innerStyles[] = 'align-items: '     . $s['contentAlignV'];
        } else {
            if (!empty($s['contentAlignV'])) $innerStyles[] = 'justify-content: ' . $s['contentAlignV'];
            if (!empty($s['contentAlignH'])) $innerStyles[] = 'align-items: '     . $s['contentAlignH'];
        }
    } elseif ($contentLayout === 'block') {
        $innerStyles[] = 'display: block';
    }

    if (!empty($s['textColor']))   $innerStyles[] = 'color: '            . $s['textColor'];
    if (!empty($s['bgColor']))     $innerStyles[] = 'background-color: ' . $s['bgColor'];
    if (intval($s['fontSize']    ?? 0) > 0) $innerStyles[] = 'font-size: '  . intval($s['fontSize'])    . 'px';
    if (!empty($s['fontWeight'])) $innerStyles[] = 'font-weight: '           . $s['fontWeight'];
    if (!empty($s['lineHeight'])) $innerStyles[] = 'line-height: '           . $s['lineHeight'];
    if (intval($s['letterSpacing'] ?? 0) > 0) $innerStyles[] = 'letter-spacing: ' . intval($s['letterSpacing']) . 'px';
    if (!empty($s['textAlign']))  $innerStyles[] = 'text-align: '             . $s['textAlign'];

    foreach (['Top', 'Right', 'Bottom', 'Left'] as $side) {
        $val = intval($s['borderSize' . $side] ?? 0);
        if ($val > 0) {
            $innerStyles[] = 'border-' . strtolower($side) . ': ' . $val . 'px solid ' . ($s['borderColor'] ?? '#000000');
        }
    }
    foreach (['TopLeft' => 'top-left', 'TopRight' => 'top-right', 'BottomRight' => 'bottom-right', 'BottomLeft' => 'bottom-left'] as $k => $css) {
        $val = intval($s['borderRadius' . $k] ?? 0);
        if ($val > 0) $innerStyles[] = 'border-' . $css . '-radius: ' . $val . 'px';
    }
    if (!empty($s['boxShadow'])) {
        $inset = ($s['boxShadowStyle'] ?? '') === 'inner' ? 'inset ' : '';
        $innerStyles[] = 'box-shadow: ' . $inset
            . intval($s['boxShadowPositionHorizontal'] ?? 0) . 'px '
            . intval($s['boxShadowPositionVertical']   ?? 0) . 'px '
            . intval($s['boxShadowBlurRadius']         ?? 0) . 'px '
            . intval($s['boxShadowSpreadRadius']       ?? 0) . 'px '
            . ($s['boxShadowColor'] ?? '#000000');
    }

    $htmlTag = $s['htmlTag'] ?? 'div';
@endphp

<{{ $htmlTag }} class="lazy-column {{ $s['cssClass'] ?? '' }}"
    @if(!empty($s['cssId'])) id="{{ $s['cssId'] }}" @endif
    style="{{ implode('; ', $outerStyles) }}">
    <div class="lazy-column-inner" style="{{ implode('; ', $innerStyles) }}">
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
