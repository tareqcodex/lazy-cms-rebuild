@php
    $s = $el['settings'] ?? [];
    
    // Visibility
    $v = $s['visibility'] ?? ['mobile' => true, 'tablet' => true, 'desktop' => true];
    $visibilityClasses = '';
    if (!($v['mobile']  ?? true)) $visibilityClasses .= ' lazy-hide-mobile';
    if (!($v['tablet']  ?? true)) $visibilityClasses .= ' lazy-hide-tablet';
    if (!($v['desktop'] ?? true)) $visibilityClasses .= ' lazy-hide-desktop';

    $wrapperStyles = [
        'text-align: ' . ($s['textAlign'] ?? 'center'),
        'padding-top: ' . ($s['paddingTop'] ?? 20) . 'px',
        'padding-bottom: ' . ($s['paddingBottom'] ?? 20) . 'px',
        'margin-top: ' . ($s['marginTop'] ?? 0) . 'px',
        'margin-right: ' . ($s['marginRight'] ?? 0) . 'px',
        'margin-bottom: ' . ($s['marginBottom'] ?? 0) . 'px',
        'margin-left: ' . ($s['marginLeft'] ?? 0) . 'px',
    ];

    $titleStyles = [
        'font-family: ' . ($s['fontFamily'] ?? 'inherit'),
        'font-size: ' . ($s['fontSize'] ?? 36) . ($s['fontSizeUnit'] ?? 'px'),
        'font-weight: ' . ($s['fontWeight'] ?? '800'),
        'line-height: ' . ($s['lineHeight'] ?? '1.2'),
        'letter-spacing: ' . ($s['letterSpacing'] ?? 0) . 'px',
        'text-transform: ' . ($s['textTransform'] ?? 'none'),
        'margin: 0',
    ];

    if (!empty($s['useGradient'])) {
        $color1 = $s['titleColor'] ?? '#222';
        $titleStyles[] = "background-image: linear-gradient(90deg, {$color1}, #0091ea)";
        $titleStyles[] = "-webkit-background-clip: text";
        $titleStyles[] = "color: transparent";
    } else {
        $titleStyles[] = 'color: ' . ($s['titleColor'] ?? '#222');
    }

    if (!empty($s['textShadow'])) {
        $h = $s['textShadowH'] ?? 0;
        $v_sh = $s['textShadowV'] ?? 0;
        $blur = $s['textShadowBlur'] ?? 0;
        $color = $s['textShadowColor'] ?? 'rgba(0,0,0,0.2)';
        $titleStyles[] = "text-shadow: {$h}px {$v_sh}px {$blur}px {$color}";
    }

    if (!empty($s['textStroke'])) {
        $size = $s['textStrokeSize'] ?? 1;
        $color = $s['textStrokeColor'] ?? '#000';
        $titleStyles[] = "-webkit-text-stroke: {$size}px {$color}";
    }

    if (!empty($s['textOverflow']) && $s['textOverflow'] !== 'initial') {
        $titleStyles[] = "text-overflow: {$s['textOverflow']}";
        $titleStyles[] = "white-space: nowrap";
        $titleStyles[] = "overflow: hidden";
    }

    // Separator
    $separator = $s['separator'] ?? 'none';
    $dividerStyles = [];
    if ($separator !== 'none') {
        $dividerStyles = [
            'width: ' . ($s['dividerWidth'] ?? 60) . 'px',
            'height: ' . ($s['dividerHeight'] ?? 3) . 'px',
            'background-color: ' . ($s['separatorColor'] ?? '#0091ea'),
        ];
        
        if ($separator !== 'default') {
            $dividerStyles['border-top'] = ($s['dividerHeight'] ?? 3) . 'px ' . $separator . ' ' . ($s['separatorColor'] ?? '#0091ea');
            $dividerStyles['background-color'] = 'transparent';
        } else {
            $dividerStyles['border-radius'] = '10px';
        }

        $align = $s['textAlign'] ?? 'center';
        if ($align === 'center') {
            $dividerStyles['margin'] = '20px auto 0';
        } elseif ($align === 'right') {
            $dividerStyles['margin'] = '20px 0 0 auto';
        } else {
            $dividerStyles['margin'] = '20px 0 0';
        }
    }

    $useLink = !empty($s['useLink']) && !empty($s['linkUrl']);
    $htmlTag = $s['htmlTag'] ?? 'h2';
    
    $linkColor = $s['linkColor'] ?? 'inherit';
    $linkHoverColor = $s['linkHoverColor'] ?? $linkColor;
    $linkId = 'title-link-' . uniqid();
@endphp

<style>
    #{{ $linkId }} { color: {{ $linkColor }} !important; text-decoration: none; display: block; transition: color 0.3s ease; }
    #{{ $linkId }}:hover { color: {{ $linkHoverColor }} !important; }
</style>

<div class="element-title-wrapper {{ $visibilityClasses }}" style="{{ implode('; ', $wrapperStyles) }}">
    @if($useLink)
        <a href="{{ $s['linkUrl'] }}" id="{{ $linkId }}">
    @endif

    <{{ $htmlTag }} class="main-title" style="{{ implode('; ', $titleStyles) }}">
        {{ $s['title'] ?? 'Your Awesome Title' }}
    </{{ $htmlTag }}>

    @if($useLink)
        </a>
    @endif

    @if($separator !== 'none')
        <div class="title-divider" style="@foreach($dividerStyles as $prop => $val) {{ $prop }}: {{ $val }}; @endforeach"></div>
    @endif
</div>
