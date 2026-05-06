@php
    $s = $container['settings'] ?? [];
    $heightMode = $s['height'] ?? 'auto';
    
    $containerStyles = [];
    if (!empty($s['bgColor'])) $containerStyles[] = "background-color: {$s['bgColor']}";
    if (!empty($s['bgImage'])) {
        $containerStyles[] = "background-image: url('{$s['bgImage']}')";
        $containerStyles[] = "background-size: " . ($s['bgImageSize'] ?? 'cover');
        $containerStyles[] = "background-position: " . ($s['bgImagePosition'] ?? 'center center');
        $containerStyles[] = "background-repeat: " . ($s['bgImageRepeat'] ?? 'no-repeat');
        $containerStyles[] = "background-attachment: " . (($s['bgImageParallax'] ?? 'none') === 'fixed' ? 'fixed' : 'scroll');
    }
    
    // Spacing
    if (isset($s['marginTop']) && $s['marginTop'] !== '') $containerStyles[] = "margin-top: {$s['marginTop']}" . ($s['marginTopUnit'] ?? 'px');
    if (isset($s['marginBottom']) && $s['marginBottom'] !== '') $containerStyles[] = "margin-bottom: {$s['marginBottom']}" . ($s['marginBottomUnit'] ?? 'px');
    if (isset($s['paddingTop']) && $s['paddingTop'] !== '') $containerStyles[] = "padding-top: {$s['paddingTop']}" . ($s['paddingTopUnit'] ?? 'px');
    if (isset($s['paddingBottom']) && $s['paddingBottom'] !== '') $containerStyles[] = "padding-bottom: {$s['paddingBottom']}" . ($s['paddingBottomUnit'] ?? 'px');
    if (isset($s['paddingLeft']) && $s['paddingLeft'] !== '') $containerStyles[] = "padding-left: {$s['paddingLeft']}" . ($s['paddingLeftUnit'] ?? 'px');
    if (isset($s['paddingRight']) && $s['paddingRight'] !== '') $containerStyles[] = "padding-right: {$s['paddingRight']}" . ($s['paddingRightUnit'] ?? 'px');

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

    $alignItems = $s['alignItems'] ?? 'stretch';

    // Align-content logic (matched with admin builder):
    // - Auto height: flex-start
    // - Fixed height: follow rowAlignContent or sync with alignItems
    if ($heightMode === 'auto') {
        $alignContentVal = 'flex-start';
    } else {
        // For fixed height, we want rows to stretch to fill the height by default,
        // which allows align-items to move columns within that space.
        $alignContentVal = $s['rowAlignContent'] ?? 'stretch';
    }

    $innerStyles = [
        'display: flex !important',
        'flex-wrap: ' . ($s['flexWrap'] ?? 'wrap'),
        'align-items: ' . $alignItems . ' !important',
        'justify-content: ' . $justifyContent,
        'align-content: ' . $alignContentVal . ' !important',
    ];

    if ($heightMode !== 'auto') {
        $val = ($heightMode === 'full' ? '100vh' : ($s['customHeight'] ?? 'auto'));
        $innerStyles[] = "min-height: {$val}";
        $innerStyles[] = "height: {$val}";
    } else {
        $hasContent = false;
        if (!empty($container['columns'])) {
            foreach ($container['columns'] as $col) {
                if (!empty($col['elements'])) {
                    $hasContent = true;
                    break;
                }
            }
        }
        // Only use 100px default if container is empty
        $innerStyles[] = "min-height: " . (!empty($s['minHeight']) ? $s['minHeight'] : ($hasContent ? '8px' : '100px'));
    }
    
    // height:100% lets inner fill the outer when outer has a fixed height (custom/full)
    $innerStyles[] = ($heightMode !== 'auto') ? "height: 100%" : "height: auto";
    $innerStyles[] = "flex-grow: 1";
    $innerStyles[] = "width: 100%";

    $contentWidth = $s['contentWidth'] ?? 'site';
    $isNestedRow = ($container['type'] ?? 'container') === 'row';
    $innerClass = ($contentWidth === 'site' && !$isNestedRow) ? 'container-custom mx-auto' : 'w-full';
    
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
                    @include('cms-dashboard::frontend.builder.column', ['column' => $column, 'container' => $container])
                @endforeach
            @endif
        </div>
        @if($link)
            </a>
        @endif
    </{{ $htmlTag }}>
@endif
