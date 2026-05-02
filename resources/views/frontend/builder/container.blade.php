@php
    $s = $container['settings'] ?? [];
    $containerStyles = [];

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
        $containerStyles[] = "background-color: " . $hexToRgba($s['bgColor'], $s['bgColorOpacity'] ?? 1);
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
        $containerStyles[] = "background-position: " . ($s['bgImagePosition'] ?? 'center center');
        $containerStyles[] = "background-repeat: " . ($s['bgImageRepeat'] ?? 'no-repeat');
        $containerStyles[] = "background-size: " . ($s['bgImageSize'] ?? 'cover');
        $containerStyles[] = "background-attachment: " . (($s['bgImageParallax'] ?? 'none') === 'fixed' ? 'fixed' : 'scroll');
        if (!empty($s['bgImageBlendMode']) && $s['bgImageBlendMode'] !== 'normal') {
            $containerStyles[] = "background-blend-mode: {$s['bgImageBlendMode']}";
        }
    }

    if (!empty($bgImages)) {
        $containerStyles[] = "background-image: " . implode(', ', $bgImages);
    }

    $containerStyles[] = "display: flex";
    $containerStyles[] = "flex-direction: column";

    // If this is a nested row, force it to take full width and break flex lines
    if (($container['type'] ?? 'container') === 'row') {
        $containerStyles[] = "flex: 0 0 100%";
        $containerStyles[] = "width: 100%";
        $containerStyles[] = "max-width: 100%";
    }

    // Height Logic
    $heightMode = $s['height'] ?? 'auto';
    if ($heightMode === 'full') {
        $containerStyles[] = "min-height: 100vh";
    } elseif ($heightMode === 'custom' && !empty($s['customHeight'])) {
        $containerStyles[] = "min-height: {$s['customHeight']}";
    } elseif (!empty($s['minHeight'])) {
        $containerStyles[] = "min-height: {$s['minHeight']}";
    } else {
        // Default min-height to ensure empty containers with backgrounds show up
        $containerStyles[] = "min-height: 8px"; 
    }
    
    if (!empty($s['maxHeight'])) {
        $containerStyles[] = "max-height: {$s['maxHeight']}";
    }
    if (isset($s['flexGrow']) && $s['flexGrow'] !== '') {
        $containerStyles[] = "flex-grow: {$s['flexGrow']}";
    }
    if (isset($s['flexShrink']) && $s['flexShrink'] !== '') {
        $containerStyles[] = "flex-shrink: {$s['flexShrink']}";
    }
    if (!empty($s['overflow']) && $s['overflow'] !== 'default') {
        $containerStyles[] = "overflow: {$s['overflow']}";
    }

    // Spacing
    if (isset($s['marginTop'])) $containerStyles[] = "margin-top: {$s['marginTop']}" . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom'])) $containerStyles[] = "margin-bottom: {$s['marginBottom']}" . ($s['marginBottomUnit'] ?? 'px');
    if (isset($s['paddingTop'])) $containerStyles[] = "padding-top: {$s['paddingTop']}" . ($s['paddingTopUnit'] ?? 'px');
    if (isset($s['paddingBottom'])) $containerStyles[] = "padding-bottom: {$s['paddingBottom']}" . ($s['paddingBottomUnit'] ?? 'px');
    if (isset($s['paddingLeft'])) $containerStyles[] = "padding-left: {$s['paddingLeft']}" . ($s['paddingLeftUnit'] ?? 'px');
    if (isset($s['paddingRight'])) $containerStyles[] = "padding-right: {$s['paddingRight']}" . ($s['paddingRightUnit'] ?? 'px');

    // Borders
    if (isset($s['borderSizeTop'])) $containerStyles[] = "border-top: {$s['borderSizeTop']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeRight'])) $containerStyles[] = "border-right: {$s['borderSizeRight']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeBottom'])) $containerStyles[] = "border-bottom: {$s['borderSizeBottom']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeLeft'])) $containerStyles[] = "border-left: {$s['borderSizeLeft']}px solid " . ($s['borderColor'] ?? '#000');
    
    // Border Radius
    if (isset($s['borderRadiusTopLeft'])) $containerStyles[] = "border-top-left-radius: {$s['borderRadiusTopLeft']}" . ($s['borderRadiusTopLeftUnit'] ?? 'px');
    if (isset($s['borderRadiusTopRight'])) $containerStyles[] = "border-top-right-radius: {$s['borderRadiusTopRight']}" . ($s['borderRadiusTopRightUnit'] ?? 'px');
    if (isset($s['borderRadiusBottomRight'])) $containerStyles[] = "border-bottom-right-radius: {$s['borderRadiusBottomRight']}" . ($s['borderRadiusBottomRightUnit'] ?? 'px');
    if (isset($s['borderRadiusBottomLeft'])) $containerStyles[] = "border-bottom-left-radius: {$s['borderRadiusBottomLeft']}" . ($s['borderRadiusBottomLeftUnit'] ?? 'px');

    // Flex/Alignment Inner
    $justifyContent = $s['justifyContent'] ?? 'flex-start';
    $isSpaceDist = in_array($justifyContent, ['space-between', 'space-around', 'space-evenly']);
    $colGap = $isSpaceDist ? '0px' : ($s['columnGap'] ?? '20px');

    $innerStyles = [
        'display: flex',
        'flex-wrap: ' . ($s['flexWrap'] ?? 'wrap'),
        'align-items: ' . ($s['alignItems'] ?? 'flex-start'),
        'justify-content: ' . $justifyContent,
        'align-content: ' . ($s['alignContent'] ?? 'stretch'),
        'column-gap: ' . $colGap,
    ];

    if ($heightMode !== 'auto') {
        $innerStyles[] = "min-height: " . ($heightMode === 'full' ? '100vh' : $s['customHeight']);
    } else {
        $innerStyles[] = "min-height: 8px";
    }
    
    $innerStyles[] = "flex-grow: 1";
    $innerStyles[] = "width: 100%";

    $contentWidth = $s['contentWidth'] ?? 'site';
    $innerClass = $contentWidth === 'site' ? 'container-custom mx-auto' : 'w-full';
    
    $htmlTag = $s['htmlTag'] ?? 'div';
    $status = $s['status'] ?? 'published';

    // Device Visibility
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';
    if (!($v['mobile'] ?? true) && !($v['tablet'] ?? true) && !($v['desktop'] ?? true)) {
        $visibilityClasses = ' lazy-hide-all';
    }
    // Hover Logic
    $hoverClass = (!empty($s['hoverType']) && $s['hoverType'] !== 'none') ? 'hover-effect-' . $s['hoverType'] : '';

    $link = !empty($s['linkUrl']) ? $s['linkUrl'] : null;
    $linkTarget = $s['linkTarget'] ?? '_self';
@endphp

@if($status === 'published')
    <{{ $htmlTag }} id="{{ $s['menuAnchor'] ?? '' }}" class="lazy-container {{ $hoverClass }} {{ $s['cssClass'] ?? '' }} {{ $visibilityClasses }}" style="{{ implode('; ', $containerStyles) }}">
        @if($link)
            <a href="{{ $link }}" target="{{ $linkTarget }}" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; flex-grow: 1; width: 100%;">
        @endif
        <div class="lazy-container-inner {{ $innerClass }} flex flex-wrap" style="{{ implode('; ', $innerStyles) }}">
            @if(!empty($container['columns']))
                @foreach($container['columns'] as $column)
                    @include('cms-dashboard::frontend.builder.column', ['column' => $column])
                @endforeach
            @endif
        </div>
        @if($link)
            </a>
        @endif
    </{{ $htmlTag }}>
@endif
