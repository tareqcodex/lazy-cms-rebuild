{{-- ═══════════════════════════════════════════════════
     Container Row  (used inside x-for: container, ci)
     ═══════════════════════════════════════════════════ --}}
<div class="group/cont w-full relative transition-none clear-both" 
     :id="'cont-'+container.id"
     :style="`padding-top: ${container.settings.marginTop || 0}px; 
              padding-bottom: ${container.settings.marginBottom || 0}px;
              z-index: ${container.settings.zIndex || 'auto'};
              position: ${container.settings.posAbsolute ? 'absolute' : (container.settings.posSticky ? 'sticky' : 'relative')};
              top: ${(container.settings.posAbsolute || container.settings.posSticky) ? (container.settings.posTop || 0) + 'px' : 'auto'};
              right: ${container.settings.posAbsolute ? (container.settings.posRight || 0) + 'px' : 'auto'};
              bottom: ${container.settings.posAbsolute ? (container.settings.posBottom || 0) + 'px' : 'auto'};
              left: ${container.settings.posAbsolute ? (container.settings.posLeft || 0) + 'px' : 'auto'};`"
     style="transition: none; overflow: visible;">

    <style>
        .cont-border-box { border: 2px solid transparent; transition: all 0.2s; }
        .cont-active .cont-border-box { border-color: #135E96; }
        
        /* Premium Canvas Toolbar */
        .lazy-toolbar {
            position: absolute; bottom: 100%; right: 0; background: #135E96; 
            display: flex; align-items: center; border-radius: 4px 4px 0 0; 
            padding: 4px; gap: 4px; opacity: 0; pointer-events: none; transition: all .15s;
            z-index: 400; box-shadow: 0 -4px 12px rgba(19,94,150,0.25);
            margin-right: 2px; height: 36px;
        }
        .cont-active .lazy-toolbar { opacity: 1; pointer-events: auto; }
        
        .lazy-btn {
            width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;
            color: #fff; background: transparent; border-radius: 3px; position: relative;
            transition: all .2s; cursor: pointer;
        }
        .lazy-btn:hover { background: rgba(255,255,255,0.2); }
        .lazy-btn svg { width: 14px; height: 14px; }

        .lazy-tooltip {
            position: absolute; bottom: 100%; left: 50%; transform: translate(-50%, -8px);
            background: #1e1e1e; color: #fff; font-size: 9px; font-weight: 800;
            padding: 5px 10px; border-radius: 4px; white-space: nowrap;
            letter-spacing: 0.5px; opacity: 0; pointer-events: none;
            transition: all .2s; z-index: 420; text-transform: uppercase;
        }
        .lazy-btn:hover .lazy-tooltip { opacity: 1; transform: translate(-50%, -12px); }

        /* Container Spacing Logic */
        .cont-margin-overlay { background: rgba(155, 89, 182, 0.08); transition: background .2s; }
        .cont-padding-overlay { background: rgba(46, 162, 204, 0.08); transition: background .2s; }
        
        .cont-handle {
            position: absolute; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;
            color: #fff; border-radius: 4px; z-index: 450; opacity: 0; pointer-events: none;
            transition: opacity 0.2s, background 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .spacing-active .cont-handle { opacity: 1 !important; pointer-events: auto !important; }
        
        .flex-stretch-child > * { height: 100%; flex: 1; }
    </style>

    <div class="group-cont-wrap" :class="[activeCi === ci ? 'cont-active' : '', (spacingEditor.type === 'container' && spacingEditor.ci === ci) ? 'spacing-active' : '']">
        
        {{-- MARGIN OVERLAYS --}}
        <div x-show="spacingEditor.type === 'container' && spacingEditor.ci === ci">
            <div class="cont-margin-overlay absolute left-0 right-0 top-0" :style="`height: ${container.settings.marginTop || 0}px;`" x-show="container.settings.marginTop > 0"></div>
            <div class="cont-margin-overlay absolute left-0 right-0 bottom-0" :style="`height: ${container.settings.marginBottom || 0}px;`" x-show="container.settings.marginBottom > 0"></div>
        </div>

        <div class="cont-border-box w-full relative overflow-visible flex flex-col" 
             :style="`min-height: ${container.settings.height === 'full' ? '100vh' : (container.settings.height === 'min' ? (container.settings.minHeight || 400)+'px' : 'auto')};
                      background: ${container.settings.bgType === 'gradient' ? 
                          (container.settings.bgGradType === 'radial' ? 'radial-gradient' : 'linear-gradient') + '(' + (container.settings.bgGradType === 'linear' ? (container.settings.bgGradAngle || 180) + 'deg, ' : '') + (container.settings.bgGradStart || '#135E96') + ' ' + (container.settings.bgGradStartPos || 0) + '%, ' + (container.settings.bgGradEnd || '#000000') + ' ' + (container.settings.bgGradEndPos || 100) + '%)' : 
                          (container.settings.bgType === 'image' && container.settings.bgImage ? `url(${container.settings.bgImage})` : (container.settings.bgColor || '#ffffff'))};
                      background-position: ${container.settings.bgImgPosition || 'center center'};
                      background-size: ${container.settings.bgImgSize || 'cover'};
                      background-repeat: ${container.settings.bgImgRepeat || 'no-repeat'};
                      background-blend-mode: ${container.settings.bgImgBlend || 'normal'};
                      border-top: ${container.settings.borderTop || 0}px solid #e2e8f0;
                      border-right: ${container.settings.borderRight || 0}px solid #e2e8f0;
                      border-bottom: ${container.settings.borderBottom || 0}px solid #e2e8f0;
                      border-left: ${container.settings.borderLeft || 0}px solid #e2e8f0;
                      border-top-left-radius: ${container.settings.radiusTopLeft || 0}px;
                      border-top-right-radius: ${container.settings.radiusTopRight || 0}px;
                      border-bottom-right-radius: ${container.settings.radiusBotRight || 0}px;
                      border-bottom-left-radius: ${container.settings.radiusBotLeft || 0}px;
                      box-shadow: ${container.settings.boxShadow ? `${container.settings.shadowInset ? 'inset' : ''} ${container.settings.shadowHorizontal || 0}px ${container.settings.shadowVertical || 0}px ${container.settings.shadowBlur || 0}px ${container.settings.shadowSpread || 0}px ${container.settings.shadowColor || 'rgba(0,0,0,0.1)'}` : 'none'};
                      overflow: ${container.settings.overflow || 'visible'};`"
             @@click.stop="activeCi = ci; activeColi = null; activeColCi = null; editingContext = {type: 'container', ci: ci, coli: null}">

            {{-- TOOLBAR --}}
            <div class="lazy-toolbar">
                <div class="lazy-btn cursor-move"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M10 9h4V6h3l-5-5-5 5h3v3zm-1 1H6V7l-5 5 5 5v-3h3v-4zm14 2l-5-5v3h-3v4h3v3l5-5zm-9 3h-4v3H7l5 5 5-5h-3v-3z"/></svg><div class="lazy-tooltip">MOVE</div></div>
                <div class="lazy-btn" @@click.stop="layout.splice(ci,1)"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg><div class="lazy-tooltip">DELETE</div></div>
                <div class="lazy-btn"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg><div class="lazy-tooltip">SAVE</div></div>
                <div class="lazy-btn" @@click.stop="cloneContainer(ci)"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg><div class="lazy-tooltip">DUPLICATE</div></div>
                <div class="lazy-btn" @@click.stop="showSpacing('container', ci); activeCi=ci; activeTab='settings'; editingContext={type:'container', ci:ci}"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM21.41 6.34l-3.75-3.75-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg><div class="lazy-tooltip">DESIGN</div></div>
                <div class="lazy-btn" @@click.stop="addEmptyContainer(ci)"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/></svg><div class="lazy-tooltip">ADD CONTAINER</div></div>
            </div>

            {{-- SPACING HANDLES (TOP) --}}
            <div x-show="spacingEditor.type === 'container' && spacingEditor.ci === ci">
                <div class="cont-handle bg-[#9b59b6] left-1/2 -translate-x-full cursor-ns-resize" :style="`top: 0px; transform: translate(-100%, -50%);`" @@mousedown.prevent="dragStart($event, ci, 'margin', 'top')">
                    <svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                </div>
                <div class="cont-handle bg-[#2ea2cc] left-1/2 translate-x-0 cursor-ns-resize" :style="`top: ${container.settings.paddingTop || 0}px; transform: translate(0, -50%);`" @@mousedown.prevent="dragStart($event, ci, 'padding', 'top')">
                    <svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                </div>
            </div>

            {{-- MASTER FLEX ENGINE --}}
            <div class="flex-1 flex flex-row flex-wrap w-full relative" 
                 :key="container.id + '_' + container.settings.align"
                 :class="container.settings.contentWidth === 'site' ? 'max-w-[1220px] mx-auto' : 'w-full'"
                 :style="{
                    padding: `${container.settings.paddingTop || 0}px ${container.settings.paddingRight || 0}px ${container.settings.paddingBottom || 0}px ${container.settings.paddingLeft || 0}px`,
                    display: 'flex !important',
                    flexDirection: 'row !important',
                    flexWrap: 'wrap !important',
                    alignContent: container.settings.height === 'auto' ? 'start' : ((container.settings.align || 'stretch') + ' !important'),
                    alignItems: container.settings.height === 'auto' ? (container.settings.align === 'flex-start' ? 'start' : (container.settings.align === 'flex-end' ? 'end' : (container.settings.align || 'center'))) : ((container.settings.align || 'stretch') + ' !important'),
                    justifyContent: (container.settings.justify || 'flex-start') + ' !important',
                    gap: (container.settings.columnSpacing || 0) + 'px',
                    height: (container.settings.height === 'auto' ? 'auto' : '100% !important'),
                    minHeight: (container.settings.height === 'auto' ? 'auto' : '100% !important')
                 }">

                <div x-show="spacingEditor.type === 'container' && spacingEditor.ci === ci" class="absolute inset-0 pointer-events-none">
                    <div class="cont-padding-overlay absolute top-0 left-0 right-0" :style="`height: ${container.settings.paddingTop || 0}px;`" x-show="container.settings.paddingTop > 0"></div>
                    <div class="cont-padding-overlay absolute bottom-0 left-0 right-0" :style="`height: ${container.settings.paddingBottom || 0}px;`" x-show="container.settings.paddingBottom > 0"></div>
                    <div class="cont-padding-overlay absolute top-0 bottom-0 left-0" :style="`width: ${container.settings.paddingLeft || 0}px;`" x-show="container.settings.paddingLeft > 0"></div>
                    <div class="cont-padding-overlay absolute top-0 bottom-0 right-0" :style="`width: ${container.settings.paddingRight || 0}px;`" x-show="container.settings.paddingRight > 0"></div>
                </div>

                <div x-show="spacingEditor.type === 'container' && spacingEditor.ci === ci">
                    <div class="cont-handle bg-[#2ea2cc] top-1/2 -translate-y-full cursor-ew-resize" :style="`left: ${container.settings.paddingLeft || 0}px; transform: translate(-50%, -100%);`" @@mousedown.prevent="dragStart($event, ci, 'padding', 'left')">
                        <svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                    </div>
                    <div class="cont-handle bg-[#2ea2cc] top-1/2 translate-y-0 cursor-ew-resize" :style="`right: ${container.settings.paddingRight || 0}px; transform: translate(50%, 0);`" @@mousedown.prevent="dragStart($event, ci, 'padding', 'right')">
                        <svg class="w-2.5 rotate-90" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                    </div>
                </div>

                <template x-if="container.columns.length === 0">
                    <div class="w-full min-h-[200px] flex items-center justify-center">
                        <button @@click="openColumnModal(ci)" class="flex flex-col items-center gap-4 text-slate-300 hover:text-[#135E96] transition-all group/add">
                            <div class="p-6 rounded-full border-2 border-dashed border-slate-200 group-hover/add:border-[#135E96] group-hover/add:bg-blue-50 transition-all"><svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg></div>
                            <span class="text-[12px] font-black uppercase tracking-[0.2em]">Select Column Layout</span>
                        </button>
                    </div>
                </template>
                
                <template x-for="(column, coli) in container.columns" :key="column.id">
                    <div :id="'col-'+column.id"
                         :style="{
                            flex: `0 0 calc(${column.basis || 100}% - ${container.columns.length > 1 ? (container.settings.columnSpacing || 0) : 0}px)`,
                            alignSelf: (column.settings.align || container.settings.align || 'stretch'),
                            display: 'flex',
                            flexDirection: 'column'
                         }"
                         class="overflow-visible relative transition-none">
                        @include('cms-dashboard::admin.builder.partials.column-cell')
                    </div>
                </template>

            </div>

             {{-- SPACING HANDLES (BOTTOM) --}}
             <div x-show="spacingEditor.type === 'container' && spacingEditor.ci === ci">
                <div class="cont-handle bg-[#2ea2cc] left-1/2 translate-x-0 cursor-ns-resize" :style="`bottom: ${container.settings.paddingBottom || 0}px; transform: translate(0, 50%);`" @@mousedown.prevent="dragStart($event, ci, 'padding', 'bottom')">
                    <svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                </div>
                <div class="cont-handle bg-[#9b59b6] left-1/2 -translate-x-full cursor-ns-resize" :style="`bottom: 0px; transform: translate(-100%, 50%);`" @@mousedown.prevent="dragStart($event, ci, 'margin', 'bottom')">
                    <svg class="w-2.5" viewBox="0 0 10 8" fill="currentColor"><rect y="0" width="10" height="1.8" rx=".9"/><rect y="3.1" width="10" height="1.8" rx=".9"/><rect y="6.2" width="10" height="1.8" rx=".9"/></svg>
                </div>
            </div>
        </div>

    </div>

</div>
