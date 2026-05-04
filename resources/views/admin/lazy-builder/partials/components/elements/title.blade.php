<div v-if="el.type === 'title'" class="element-title-wrapper" :class="getVisibilityClasses(el.settings)">
    <div :style="{
        textAlign: el.settings.textAlign || 'center',
        paddingTop: getUnitVal(el.settings.paddingTop, el.settings.paddingTopUnit),
        paddingBottom: getUnitVal(el.settings.paddingBottom, el.settings.paddingBottomUnit),
        marginTop: getUnitVal(el.settings.marginTop, 'px'),
        marginRight: getUnitVal(el.settings.marginRight, 'px'),
        marginBottom: getUnitVal(el.settings.marginBottom, 'px'),
        marginLeft: getUnitVal(el.settings.marginLeft, 'px'),
    }">
        <a :href="el.settings.useLink && el.settings.linkUrl ? el.settings.linkUrl : 'javascript:void(0)'" 
           @mouseenter="el.isHovered = true"
           @mouseleave="el.isHovered = false"
           :style="{ 
               pointerEvents: el.settings.useLink ? 'auto' : 'none', 
               textDecoration: 'none', 
               color: el.settings.useLink ? (el.isHovered ? (el.settings.linkHoverColor || el.settings.linkColor) : (el.settings.linkColor || 'inherit')) : 'inherit', 
               display: 'block',
               transition: 'color 0.3s ease'
           }">
            
            <component :is="el.settings.htmlTag || 'h2'" 
                :style="{ 
                    color: el.settings.useGradient ? 'transparent' : (el.settings.titleColor || '#222'), 
                    backgroundImage: el.settings.useGradient ? 'linear-gradient(90deg, ' + (el.settings.titleColor || '#222') + ', #0091ea)' : 'none',
                    webkitBackgroundClip: el.settings.useGradient ? 'text' : 'none',
                    fontFamily: el.settings.fontFamily || 'inherit',
                    fontSize: getUnitVal(el.settings.fontSize || 36, el.settings.fontSizeUnit || 'px'), 
                    fontWeight: el.settings.fontWeight || '800', 
                    lineHeight: el.settings.lineHeight || '1.2', 
                    letterSpacing: getUnitVal(el.settings.letterSpacing, 'px'),
                    textTransform: el.settings.textTransform || 'none',
                    textShadow: el.settings.textShadow ? (el.settings.textShadowH || 0) + 'px ' + (el.settings.textShadowV || 0) + 'px ' + (el.settings.textShadowBlur || 0) + 'px ' + (el.settings.textShadowColor || 'rgba(0,0,0,0.2)') : 'none',
                    webkitTextStroke: el.settings.textStroke ? (el.settings.textStrokeSize || 1) + 'px ' + (el.settings.textStrokeColor || '#000') : 'none',
                    textOverflow: el.settings.textOverflow || 'initial',
                    whiteSpace: (el.settings.textOverflow === 'ellipsis' || el.settings.textOverflow === 'clip') ? 'nowrap' : 'normal',
                    overflow: (el.settings.textOverflow === 'ellipsis' || el.settings.textOverflow === 'clip') ? 'hidden' : 'visible',
                    margin: '0' 
                }" 
                class="main-title">@{{ el.settings.title || 'Your Awesome Title' }}</component>
        </a>

        <!-- Separator (Renamed from Divider) -->
        <div v-if="el.settings.separator && el.settings.separator !== 'none'" 
             :style="{ 
                 width: getUnitVal(el.settings.dividerWidth || 60, 'px'), 
                 height: getUnitVal(el.settings.dividerHeight || 3, 'px'), 
                 backgroundColor: el.settings.separatorColor || '#0091ea',
                 borderTop: el.settings.separator !== 'default' ? (el.settings.dividerHeight || 3) + 'px ' + el.settings.separator + ' ' + (el.settings.separatorColor || '#0091ea') : 'none',
                 margin: el.settings.textAlign === 'center' ? '20px auto 0' : (el.settings.textAlign === 'right' ? '20px 0 0 auto' : '20px 0 0'),
                 borderRadius: el.settings.separator === 'default' ? '10px' : '0'
             }" 
             class="title-divider"></div>
    </div>
</div>
