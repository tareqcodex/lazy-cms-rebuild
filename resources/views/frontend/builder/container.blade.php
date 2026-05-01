@php
    $s = $container['settings'] ?? [];
    $containerStyles = [];
    
    // Background Logic
    if (($s['bgType'] ?? 'color') === 'color' && !empty($s['bgColor'])) {
        $containerStyles[] = "background-color: {$s['bgColor']}";
    } elseif (($s['bgType'] ?? 'color') === 'gradient') {
        $angle = $s['bgGradientAngle'] ?? 180;
        $start = $s['bgGradientStartColor'] ?? '#ffffff';
        $end = $s['bgGradientEndColor'] ?? '#000000';
        $containerStyles[] = "background: linear-gradient({$angle}deg, {$start}, {$end})";
    }

    if (!empty($s['bgImage'])) {
        $containerStyles[] = "background-image: url('{$s['bgImage']}')";
        $containerStyles[] = "background-position: " . ($s['bgImagePosition'] ?? 'center center');
        $containerStyles[] = "background-repeat: " . ($s['bgImageRepeat'] ?? 'no-repeat');
        $containerStyles[] = "background-size: " . ($s['bgImageSize'] ?? 'cover');
    }

    // Height Logic
    $heightMode = $s['height'] ?? 'auto';
    if ($heightMode === 'full') {
        $containerStyles[] = "min-height: 100vh";
    } elseif ($heightMode === 'custom' && !empty($s['customHeight'])) {
        $containerStyles[] = "min-height: {$s['customHeight']}";
    }

    // Spacing
    if (isset($s['marginTop'])) $containerStyles[] = "margin-top: {$s['marginTop']}px";
    if (isset($s['marginBottom'])) $containerStyles[] = "margin-bottom: {$s['marginBottom']}px";
    if (isset($s['paddingTop'])) $containerStyles[] = "padding-top: {$s['paddingTop']}px";
    if (isset($s['paddingBottom'])) $containerStyles[] = "padding-bottom: {$s['paddingBottom']}px";
    if (isset($s['paddingLeft'])) $containerStyles[] = "padding-left: {$s['paddingLeft']}px";
    if (isset($s['paddingRight'])) $containerStyles[] = "padding-right: {$s['paddingRight']}px";

    // Borders
    if (isset($s['borderSizeTop'])) $containerStyles[] = "border-top: {$s['borderSizeTop']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeRight'])) $containerStyles[] = "border-right: {$s['borderSizeRight']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeBottom'])) $containerStyles[] = "border-bottom: {$s['borderSizeBottom']}px solid " . ($s['borderColor'] ?? '#000');
    if (isset($s['borderSizeLeft'])) $containerStyles[] = "border-left: {$s['borderSizeLeft']}px solid " . ($s['borderColor'] ?? '#000');
    
    // Border Radius
    if (isset($s['borderRadiusTopLeft'])) $containerStyles[] = "border-top-left-radius: {$s['borderRadiusTopLeft']}px";
    if (isset($s['borderRadiusTopRight'])) $containerStyles[] = "border-top-right-radius: {$s['borderRadiusTopRight']}px";
    if (isset($s['borderRadiusBottomRight'])) $containerStyles[] = "border-bottom-right-radius: {$s['borderRadiusBottomRight']}px";
    if (isset($s['borderRadiusBottomLeft'])) $containerStyles[] = "border-bottom-left-radius: {$s['borderRadiusBottomLeft']}px";

    // Flex/Alignment Inner
    $innerStyles = [
        'display: flex',
        'flex-wrap: ' . ($s['flexWrap'] ?? 'wrap'),
        'align-items: ' . ($s['alignItems'] ?? 'flex-start'),
        'justify-content: ' . ($s['justifyContent'] ?? 'flex-start'),
        'align-content: ' . ($s['alignContent'] ?? 'stretch'),
    ];

    if ($heightMode !== 'auto') {
        $innerStyles[] = "min-height: " . ($heightMode === 'full' ? '100vh' : $s['customHeight']);
    }

    $contentWidth = $s['contentWidth'] ?? 'site';
    $innerClass = $contentWidth === 'site' ? 'theme-container' : 'w-full';
    
    $htmlTag = $s['htmlTag'] ?? 'div';
    $status = $s['status'] ?? 'published';
@endphp

@if($status === 'published')
    <{{ $htmlTag }} id="{{ $s['menuAnchor'] ?? '' }}" class="lazy-container {{ $s['cssClass'] ?? '' }}" style="{{ implode('; ', $containerStyles) }}">
        <div class="lazy-container-inner {{ $innerClass }} flex flex-wrap" style="{{ implode('; ', $innerStyles) }}">
            @if(!empty($container['columns']))
                @foreach($container['columns'] as $column)
                    @include('cms-dashboard::frontend.builder.column', ['column' => $column])
                @endforeach
            @endif
        </div>
    </{{ $htmlTag }}>
@endif
