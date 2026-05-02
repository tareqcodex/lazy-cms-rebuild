@php
    $s     = $column['settings'] ?? [];
    $basisRaw = $column['basis'] ?? null;
    if ($basisRaw === null) {
        $totalColumns = max(1, count($container['columns'] ?? [1]));
        $basisRaw = (100 / $totalColumns) . '%';
    }

    $flexBasis = $basisRaw;
    if ($basisRaw === 'auto') {
        $flexBasis = 'auto';
    } elseif (is_string($basisRaw) && strpos($basisRaw, '%') !== false) {
        $flexBasis = "calc({$basisRaw} - 15px)";
    } elseif (is_numeric($basisRaw)) {
        $flexBasis = "calc({$basisRaw}% - 15px)";
    }

    $flexGrow = (isset($s['flexGrow']) && $s['flexGrow'] !== '') ? $s['flexGrow'] : 0;
    $flexShrink = (isset($s['flexShrink']) && $s['flexShrink'] !== '') ? $s['flexShrink'] : 0;
    $maxWidth = $flexBasis === 'auto' ? 'none' : $flexBasis;

    // Device Visibility (Responsive)
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
    if (!($v['mobile'] ?? true) && !($v['tablet'] ?? true) && !($v['desktop'] ?? true)) {
        $visibilityClasses = ' lazy-hide-all';
    }

    $outerStyles = [
        "flex-basis: {$flexBasis}",
        "flex-grow: {$flexGrow}",
        "flex-shrink: {$flexShrink}",
        "max-width: {$maxWidth}",
    ];

    if (!empty($s['alignment']) && $s['alignment'] !== 'default') {
        $outerStyles[] = 'align-self: ' . $s['alignment'];
    }
    if (isset($s['marginTop']) && $s['marginTop'] !== '') $outerStyles[] = 'margin-top: ' . $s['marginTop'] . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $outerStyles[] = 'margin-bottom: ' . $s['marginBottom'] . ($s['marginBottomUnit'] ?? 'px');

    // Inner: padding, layout, typography, background, border, shadow
    $innerStyles = [
        'height: 100%',
        'min-height: 8px',
        'padding-top: '    . ($s['paddingTop']    ?? 10) . ($s['paddingTopUnit'] ?? 'px'),
        'padding-bottom: ' . ($s['paddingBottom'] ?? 10) . ($s['paddingBottomUnit'] ?? 'px'),
        'padding-left: '   . ($s['paddingLeft']   ?? 10) . ($s['paddingLeftUnit'] ?? 'px'),
        'padding-right: '  . ($s['paddingRight']  ?? 10) . ($s['paddingRightUnit'] ?? 'px'),
        'box-sizing: border-box',
    ];
    if (isset($s['marginLeft']) && $s['marginLeft'] !== '') $innerStyles[] = 'margin-left: ' . $s['marginLeft'] . ($s['marginLeftUnit'] ?? 'px');
    if (isset($s['marginRight']) && $s['marginRight'] !== '') $innerStyles[] = 'margin-right: ' . $s['marginRight'] . ($s['marginRightUnit'] ?? 'px');

    $contentLayout = $s['contentLayout'] ?? '';
    if ($contentLayout && $contentLayout !== 'block') {
        $innerStyles[] = 'display: flex';
        $innerStyles[] = 'flex-wrap: wrap';
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

    $hexToRgba = function($hex, $opacity) {
        if (empty($hex) || $hex === 'transparent') return 'transparent';
        if (strpos($hex, 'rgba') !== false) return $hex;
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        return "rgba($r, $g, $b, $opacity)";
    };

    // Background Logic
    if (!empty($s['bgColor'])) {
        $innerStyles[] = "background-color: " . $hexToRgba($s['bgColor'], $s['bgColorOpacity'] ?? 1);
    }

    $bgImages = [];
    if (($s['bgType'] ?? 'color') === 'gradient' && !empty($s['bgGradientStartColor']) && !empty($s['bgGradientEndColor'])) {
        $gType = $s['bgGradientType'] ?? 'linear';
        $angle = $s['bgGradientAngle'] ?? 180;
        $start = $hexToRgba($s['bgGradientStartColor'], $s['bgGradientStartOpacity'] ?? 1);
        $end   = $hexToRgba($s['bgGradientEndColor'],   $s['bgGradientEndOpacity']   ?? 1);
        $startPos = $s['bgGradientStartPosition'] ?? 0;
        $endPos = $s['bgGradientEndPosition'] ?? 100;

        if ($gType === 'linear') {
            $bgImages[] = "linear-gradient({$angle}deg, {$start} {$startPos}%, {$end} {$endPos}%)";
        } else {
            $bgImages[] = "radial-gradient(circle at center, {$start} {$startPos}%, {$end} {$endPos}%)";
        }
    }

    if (!empty($s['bgImage'])) {
        $bgImages[] = "url('{$s['bgImage']}')";
        $innerStyles[] = "background-position: " . ($s['bgImagePosition'] ?? 'center center');
        $innerStyles[] = "background-repeat: " . ($s['bgImageRepeat'] ?? 'no-repeat');
        $innerStyles[] = "background-size: " . ($s['bgImageSize'] ?? 'cover');
        $innerStyles[] = "background-attachment: " . (($s['bgImageParallax'] ?? 'none') === 'fixed' ? 'fixed' : 'scroll');
        if (!empty($s['bgImageBlendMode']) && $s['bgImageBlendMode'] !== 'normal') {
            $innerStyles[] = "background-blend-mode: {$s['bgImageBlendMode']}";
        }
    }

    if (!empty($bgImages)) {
        $innerStyles[] = "background-image: " . implode(', ', $bgImages);
    }
    if (isset($s['fontSize']) && $s['fontSize'] !== '') $innerStyles[] = 'font-size: ' . $s['fontSize'] . ($s['fontSizeUnit'] ?? 'px');
    if (!empty($s['fontWeight'])) $innerStyles[] = 'font-weight: '           . $s['fontWeight'];
    if (!empty($s['lineHeight'])) $innerStyles[] = 'line-height: '           . $s['lineHeight'];
    if (isset($s['letterSpacing']) && $s['letterSpacing'] !== '') $innerStyles[] = 'letter-spacing: ' . $s['letterSpacing'] . ($s['letterSpacingUnit'] ?? 'px');
    if (!empty($s['textAlign']))  $innerStyles[] = 'text-align: '             . $s['textAlign'];

    foreach (['Top', 'Right', 'Bottom', 'Left'] as $side) {
        $val = intval($s['borderSize' . $side] ?? 0);
        if ($val > 0) {
            $innerStyles[] = 'border-' . strtolower($side) . ': ' . $val . 'px solid ' . ($s['borderColor'] ?? '#000000');
        }
    }
    foreach (['TopLeft' => 'top-left', 'TopRight' => 'top-right', 'BottomRight' => 'bottom-right', 'BottomLeft' => 'bottom-left'] as $k => $css) {
        $val = $s['borderRadius' . $k] ?? null;
        if ($val !== null && $val !== '') $innerStyles[] = 'border-' . $css . '-radius: ' . $val . ($s['borderRadius' . $k . 'Unit'] ?? 'px');
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
    $link = !empty($s['linkUrl']) ? $s['linkUrl'] : null;
@endphp

@php
    $hoverClass = (!empty($s['hoverType']) && $s['hoverType'] !== 'none') ? 'hover-effect-' . $s['hoverType'] : '';
@endphp

<{{ $htmlTag }} class="lazy-column {{ $hoverClass }} {{ $visibilityClasses }} {{ $s['cssClass'] ?? '' }}"
    @if(!empty($s['cssId'])) id="{{ $s['cssId'] }}" @endif
    style="{{ implode('; ', $outerStyles) }}">
    
    @if($link)
        <a href="{{ $link }}" target="{{ $s['linkTarget'] ?? '_self' }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; height: 100%; width: 100%;">
    @endif

    <div class="lazy-column-inner" style="{{ implode('; ', $innerStyles) }}">
        @if(!empty($column['elements']))
            @foreach($column['elements'] as $el)
                @if($el['type'] === 'heading')
                    @include('cms-dashboard::frontend.builder.elements.heading', ['el' => $el])
                @elseif($el['type'] === 'text')
                    @include('cms-dashboard::frontend.builder.elements.text', ['el' => $el])
                @elseif($el['type'] === 'row')
                    @if($contentLayout === 'row')
                        <div style="flex-basis: 100%; width: 100%; height: 0; overflow: hidden;"></div>
                    @endif
                    {{-- Render the row element as if it were a container --}}
                    @include('cms-dashboard::frontend.builder.container', ['container' => $el])
                    @if($contentLayout === 'row')
                        <div style="flex-basis: 100%; width: 100%; height: 0; overflow: hidden;"></div>
                    @endif
                @endif
            @endforeach
        @endif
    </div>

    @if($link)
        </a>
    @endif
</{{ $htmlTag }}>
