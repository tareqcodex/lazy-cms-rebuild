<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Builder – Lazy CMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('vendor/cms-dashboard/js/tailwind.min.js') }}"></script>
    <script defer src="{{ asset('vendor/cms-dashboard/js/alpine.min.js') }}"></script>
    <link href="{{ asset('vendor/cms-dashboard/css/inter.css') }}" rel="stylesheet">
    <style>
        *{ box-sizing:border-box; }
        body{ font-family:'Inter',-apple-system,sans-serif; margin:0; padding:0; background:#efefef; overflow:hidden; }
        [x-cloak]{ display:none !important; }

        /* ── Layout skeleton ── */
        #builder-root{ display:flex; flex-direction:column; height:100vh; width:100vw; }
        #builder-topbar{ height:46px; flex-shrink:0; z-index:100; }
        #builder-body{ display:flex; flex:1; overflow:hidden; }
        #builder-sidebar{ flex-shrink:0; z-index:50; position:relative; }
        #builder-canvas{ flex:1; overflow-y:auto; min-width:0; background:#fff; }

        /* ── Floating drag pixel label ── */
        #drag-label{
            position:fixed; z-index:9999; color:#fff;
            font-family:'Inter',sans-serif; font-size:10px; font-weight:700;
            padding:2px 7px; border-radius:3px; pointer-events:none; display:none;
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar{ width:5px; height:5px; }
        ::-webkit-scrollbar-track{ background:transparent; }
        ::-webkit-scrollbar-thumb{ background:#ccc; border-radius:3px; }

        /* ── Container spacing sections ── */
        .margin-section{ background:#dde8f4; }
        .padding-section{ background:#c6d9ee; }

        /* ── Spacing handle button ── */
        .sp-handle{
            width:20px; height:20px; border-radius:2px;
            display:flex; align-items:center; justify-content:center;
            cursor:ns-resize; flex-shrink:0;
            transition: opacity .15s;
        }

        /* ── Side drag handle ── */
        .side-handle{
            width:14px; height:38px; border-radius:2px;
            display:flex; align-items:center; justify-content:center;
            cursor:ew-resize; flex-shrink:0;
        }

        /* ── Container toolbar ── */
        .c-toolbar{
            position:absolute; top:0; right:0;
            transform:translateY(-100%);
            display:flex; align-items:center;
            border-radius:3px 3px 0 0;
            overflow:hidden;
            opacity:0; pointer-events:none;
            transition:opacity .15s;
            box-shadow:0 -3px 10px rgba(0,0,0,.15);
            z-index:50;
        }
        .group\/cont:hover .c-toolbar{ opacity:1; pointer-events:auto; }
        .c-toolbar .c-tip{
            position:absolute; bottom:100%; right:0;
            background:#1e1e1e; color:#fff;
            font-size:8px; font-weight:700;
            padding:3px 9px; letter-spacing:1.5px; text-transform:uppercase;
            white-space:nowrap; border-radius:2px 2px 0 0;
        }
        .c-toolbar button{
            width:28px; height:28px; display:flex; align-items:center; justify-content:center;
            background:#2ea2cc; border-right:1px solid rgba(255,255,255,.18); color:#fff;
            transition:background .12s;
        }
        .c-toolbar button:last-child{ border-right:none; }
        .c-toolbar button:hover{ background:#2388ab; }
        .c-toolbar button.danger:hover{ background:#ef4444; }
        .c-toolbar button svg{ width:12px; height:12px; }

        /* ── Column hover ── */
        .col-cell{ position:relative; }
        .col-cell .col-toolbar{
            position:absolute; top:0; left:0;
            transform:translateY(-100%);
            display:flex; align-items:center;
            border-radius:3px 3px 0 0; overflow:hidden;
            opacity:0; pointer-events:none;
            transition:opacity .15s;
            box-shadow:0 -3px 10px rgba(0,0,0,.18);
            z-index:40;
        }
        .col-cell:hover .col-toolbar{ opacity:1; pointer-events:auto; }
        .col-cell .col-tip{
            position:absolute; bottom:100%; left:0;
            background:#1e1e1e; color:#fff;
            font-size:8px; font-weight:700;
            padding:3px 8px; letter-spacing:1.5px; text-transform:uppercase;
            white-space:nowrap; border-radius:2px 2px 0 0;
        }
        .col-cell .col-toolbar button,
        .col-cell .col-toolbar .col-lbl{
            height:26px; display:flex; align-items:center; justify-content:center;
            padding:0 8px; border-right:1px solid rgba(255,255,255,.1); font-size:9px; font-weight:700;
        }
        .col-cell .col-toolbar button{ background:#1e1e1e; color:#fff; transition:background .12s; }
        .col-cell .col-toolbar button:last-child{ border-right:none; }
        .col-cell .col-toolbar button:hover{ background:#333; }
        .col-cell .col-toolbar .col-lbl{ background:#2ea2cc; color:#fff; min-width:28px; }
        .col-cell .col-toolbar button svg{ width:11px; height:11px; }

        /* ── Selector modal ── */
        #col-modal{ display:flex; position:fixed; inset:0; z-index:200; background:rgba(0,0,0,.6); align-items:center; justify-content:center; }

        /* ── Standard WP Admin Styles (For Media Modal parity) ── */
        .wp-btn-primary { background: #2271b1; color: #fff; border: 1px solid #2271b1 !important; border-radius: 3px; padding: 0 10px; min-height: 30px; font-size: 13px; line-height: 2.15384615; cursor: pointer; transition: all 0.1s; display: inline-flex; align-items: center; justify-content: center; }
        .wp-btn-primary:hover { background: #135e96; border-color: #135e96; }
        .wp-btn-secondary { background: #f6f7f7; color: #2271b1; border: 1px solid #2271b1 !important; border-radius: 3px; padding: 0 10px; min-height: 30px; font-size: 13px; line-height: 2.15384615; cursor: pointer; transition: all 0.1s; display: inline-flex; align-items: center; justify-content: center; }
        .wp-btn-secondary:hover { background: #f0f0f1; border-color: #0a4b78; color: #0a4b78; }
        .wp-input { border: 1px solid #8c8f94; border-radius: 3px; box-shadow: 0 0 0 transparent; padding: 0 8px; min-height: 30px; font-size: 14px; background: #fff; }
        .wp-input:focus { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; outline: none; }
    </style>
</head>
<body x-data="builderApp()" @mousemove="onMouseMove($event)" @mouseup="onMouseUp()">

    <div id="drag-label"></div>

    <div id="builder-root">
        <div id="builder-topbar">
            @include('cms-dashboard::admin.builder.partials.topbar')
        </div>
        <div id="builder-body">
            <div id="builder-sidebar" :style="`width: ${sidebarWidth}px`">
                @include('cms-dashboard::admin.builder.partials.sidebar')
            </div>
            {{-- Resize drag handle --}}
            <div class="flex-shrink-0 w-1 cursor-col-resize hover:bg-[#2ea2cc] bg-transparent transition-colors group relative z-50"
                 @@mousedown.prevent="startSidebarDrag($event)"
                 title="Drag to resize panel">
                <div class="absolute inset-y-0 -left-1 -right-1 cursor-col-resize"></div>
            </div>
            <div id="builder-canvas">
                @include('cms-dashboard::admin.builder.partials.canvas')
            </div>
        </div>
    </div>

    <x-cms-dashboard::admin.media-modal />

    @include('cms-dashboard::admin.builder.partials.modal-select-column')
    @include('cms-dashboard::admin.builder.partials.modal-select-element')

    {{-- Toast Notification --}}
    <div x-show="toast.show" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="fixed bottom-10 right-10 z-[2000] pointer-events-none">
        <div class="bg-[#2c3338] text-white px-6 py-3.5 rounded shadow-2xl flex items-center gap-3 border-l-4"
             :class="toast.type === 'error' ? 'border-red-500' : 'border-[#2ea2cc]'">
            <svg x-show="toast.type !== 'error'" class="w-5 h-5 text-[#2ea2cc]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
            <svg x-show="toast.type === 'error'" class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="text-[13px] font-bold tracking-wide uppercase" x-text="toast.message"></span>
        </div>
    </div>

    <script>
    function builderApp(){
        return {
            activeTab:'navigator',
            showModal:false,
            layout: @json(json_decode(($post->editor_type === 'builder' && $post->content) ? $post->content : '[]') ?: []),
            isSaving: false,
            toast: { show: false, message: '', type: 'success' },
            drag:null,
            sidebarWidth:330,
            sidebarDrag:false,
            sidebarDragStartX:0,
            sidebarDragStartWidth:330,
            targetCi: null,
            targetColi: null,
            isInserting: false,
            activeCi: null, // Tracks currently selected container
            activeColi: null, // Tracks currently selected column index
            activeColCi: null, // Tracks CI of the active column
            showElementModal: false,
            elementContext: { ci: null, coli: null },
            spacingEditor: { type: null, ci: null, coli: null }, 
            editingContext: { type: null, ci: null, coli: null }, // Context for the settings panel
            defaultContainer: {
                id: null,
                settings: { 
                    marginTop: 0, marginBottom: 0, paddingTop: 60, paddingBottom: 60, paddingLeft: 0, paddingRight: 0,
                    contentWidth: 'site', height: 'auto', minHeight: 400,
                    rowAlign: 'center', align: 'flex-start', justify: 'flex-start',
                    wrap: 'wrap', columnSpacing: 20, htmlTag: 'div',
                    visibility: { mobile: true, tablet: true, desktop: true },
                    status: 'published',
                    linkColor: '#135E96',
                    borderTop: 0, borderRight: 0, borderBottom: 0, borderLeft: 0,
                    radiusTopLeft: 0, radiusTopRight: 0, radiusBotRight: 0, radiusBotLeft: 0,
                    boxShadow: false, 
                    shadowVertical: 0, shadowHorizontal: 0, shadowBlur: 10, shadowSpread: 0, 
                    shadowColor: 'rgba(0,0,0,0.1)', shadowInset: false,
                    bgType: 'color', bgColor: '#ffffff', bgImage: '',
                    bgGradStart: '#135E96', bgGradEnd: '#000000', bgGradAngle: 180,
                    bgGradStartPos: 0, bgGradEndPos: 100, bgGradType: 'linear',
                    bgImgPosition: 'center center', bgImgRepeat: 'no-repeat', bgImgSize: 'cover', bgImgBlend: 'normal',
                    bgVideo: '',
                    zIndex: 'auto', overflow: 'visible',
                    posAbsolute: false, posAbsoluteDevices: { mobile: false, tablet: false, desktop: false },
                    posTop: 0, posRight: 0, posBottom: 0, posLeft: 0,
                    posSticky: false, posStickyDevices: { mobile: false, tablet: false, desktop: false },
                    stickyBg: '#ffffff', stickyOffset: 0, stickyTransOffset: 0, stickyHideOnScroll: 0
                },
                columns: []
            },

            columnPresets:[
                {label:'1/1',           boxes:[1],           fracs:['1/1']},
                {label:'1/2 - 1/2',     boxes:[1,1],         fracs:['1/2','1/2']},
                {label:'1/3 - 1/3 - 1/3',boxes:[1,1,1],     fracs:['1/3','1/3','1/3']},
                {label:'1/4 - 1/4 - 1/4 - 1/4',boxes:[1,1,1,1],fracs:['1/4','1/4','1/4','1/4']},
                {label:'2/3 - 1/3',     boxes:[2,1],         fracs:['2/3','1/3']},
                {label:'1/3 - 2/3',     boxes:[1,2],         fracs:['1/3','2/3']},
                {label:'1/4 - 3/4',     boxes:[1,3],         fracs:['1/4','3/4']},
                {label:'3/4 - 1/4',     boxes:[3,1],         fracs:['3/4','1/4']},
                {label:'1/2 - 1/4 - 1/4',boxes:[2,1,1],     fracs:['1/2','1/4','1/4']},
                {label:'1/4 - 1/4 - 1/2',boxes:[1,1,2],     fracs:['1/4','1/4','1/2']},
                {label:'1/4 - 1/2 - 1/4',boxes:[1,2,1],     fracs:['1/4','1/2','1/4']},
                {label:'1/5 - 4/5',     boxes:[1,4],         fracs:['1/5','4/5']},
                {label:'4/5 - 1/5',     boxes:[4,1],         fracs:['4/5','1/5']},
                {label:'3/5 - 2/5',     boxes:[3,2],         fracs:['3/5','2/5']},
                {label:'2/5 - 3/5',     boxes:[2,3],         fracs:['2/5','3/5']},
                {label:'1/5 - 1/5 - 3/5',boxes:[1,1,3],     fracs:['1/5','1/5','3/5']},
                {label:'1/5 - 3/5 - 1/5',boxes:[1,3,1],     fracs:['1/5','3/5','1/5']},
                {label:'1/2 - 1/6 - 1/6 - 1/6',boxes:[3,1,1,1],fracs:['1/2','1/6','1/6','1/6']},
                {label:'1/6 - 1/6 - 1/6 - 1/2',boxes:[1,1,1,3],fracs:['1/6','1/6','1/6','1/2']},
                {label:'1/6 - 2/3 - 1/6',boxes:[1,4,1],     fracs:['1/6','2/3','1/6']},
                {label:'1/5 - 1/5 - 1/5 - 1/5 - 1/5',boxes:[1,1,1,1,1],fracs:['1/5','1/5','1/5','1/5','1/5']},
                {label:'1/6 - 1/6 - 1/6 - 1/6 - 1/6 - 1/6',boxes:[1,1,1,1,1,1],fracs:['1/6','1/6','1/6','1/6','1/6','1/6']},
                {label:'5/6',           boxes:[5,1],         fracs:['5/6','1/6']},
                {label:'4/5',           boxes:[4,1],         fracs:['4/5','1/5']},
                {label:'3/4',           boxes:[3,1],         fracs:['3/4','1/4']},
                {label:'2/3',           boxes:[2,1],         fracs:['2/3','1/3']},
                {label:'3/5',           boxes:[3,2],         fracs:['3/5','2/5']},
                {label:'1/2',           boxes:[1],           fracs:['1/2']},
                {label:'2/5',           boxes:[2,3],         fracs:['2/5','3/5']},
                {label:'1/3',           boxes:[1,2],         fracs:['1/3','2/3']},
                {label:'1/4',           boxes:[1,3],         fracs:['1/4','3/4']},
                {label:'1/5',           boxes:[1,4],         fracs:['1/5','4/5']},
                {label:'1/6',           boxes:[1,5],         fracs:['1/6','5/6']},
            ],

            async saveLayout() {
                this.isSaving = true;
                try {
                    const response = await fetch(`{{ route('admin.builder.save', $post->id) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ layout: this.layout })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.triggerToast(data.message || 'Saved successfully');
                    }
                } catch (error) {
                    this.triggerToast('Error saving layout', 'error');
                    console.error('Error saving layout:', error);
                } finally {
                    this.isSaving = false;
                }
            },

            triggerToast(message, type = 'success') {
                this.toast.message = message;
                this.toast.type = type;
                this.toast.show = true;
                setTimeout(() => { this.toast.show = false; }, 3000);
            },

            async previewLayout() {
                await this.saveLayout();
                window.open(`{{ route('admin.builder.preview', $post->id) }}`, '_blank');
            },

            openMedia(context){
                window.openMediaModal((media) => {
                    const ci = context.ci;
                    this.layout[ci].settings.bgImage = `/storage/${media.path}`;
                });
            },

            openColumnModal(ci = null, coli = null){
                this.targetCi = ci;
                this.targetColi = coli;
                this.isInserting = false;
                this.showSpacing(null, null);
                this.showModal = true;
            },
            
            showSpacing(type, ci, coli = null) {
                this.spacingEditor = { type, ci, coli };
                if (type) {
                    this.editingContext = { type, ci, coli };
                    this.activeTab = 'settings';
                }
            },

            duplicateNestedColumn(nestedRow, ncoli) {
                const ts = Date.now();
                const clone = JSON.parse(JSON.stringify(nestedRow.columns[ncoli]));
                
                // Regenerate IDs for the cloned column and its children
                clone.id = 'ncol_' + ts + '_dup';
                if (clone.children) {
                    clone.children.forEach((child, index) => {
                        child.id = 'nc_' + ts + '_' + index;
                    });
                }

                nestedRow.columns.splice(ncoli + 1, 0, clone);
            },

            addEmptyContainer(ci) {
                this.targetCi = ci; // Insert AFTER this index
                this.isInserting = true;
                this.showModal = true;
            },
            
            clearSpacing() {
                this.spacingEditor = { type: null, ci: null, coli: null };
            },

            scrollTo(ci, coli = null) {
                if(coli !== null) {
                    this.activeColi = coli;
                    this.activeColCi = ci;
                    this.activeCi = null;
                    this.showSpacing('column', ci, coli);
                } else {
                    this.activeCi = ci;
                    this.activeColi = null;
                    this.activeColCi = null;
                    this.showSpacing('container', ci);
                }

                this.$nextTick(() => {
                    const id = coli !== null ? `col-${this.layout[ci].columns[coli].id}` : `cont-${this.layout[ci].id}`;
                    const el = document.getElementById(id);
                    if(el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            },

            openElementModal(ci, coli, isNested = false){
                this.elementContext = { ci, coli, isNested };
                this.showElementModal = true;
            },

            insertNestedColumns(preset){
                if(this.elementContext.ci === null || this.elementContext.coli === null) return;
                if(this.elementContext.isNested) return; // Prevent nested columns inside nested columns
                const ci = this.elementContext.ci;
                const coli = this.elementContext.coli;
                const ts = Date.now();
                
                const nestedRow = {
                    id: 'nest_'+ts,
                    type: 'nested_row',
                    columns: preset.boxes.map((f, i) => {
                        const fr = preset.fracs[i].split('/');
                        return {
                            id: 'ncol_'+ts+'_'+i,
                            flex: f,
                            frac: preset.fracs[i],
                            basis: (parseInt(fr[0])/parseInt(fr[1])) * 100,
                            settings: { 
                                paddingTop: 10, paddingBottom: 10, paddingLeft: 10, paddingRight: 10,
                                align: 'flex-start',
                                contentLayout: 'column', 
                                contentAlign: 'flex-start',
                                contentVAlign: 'stretch',
                                htmlTag: 'div',
                                linkUrl: '',
                                cssClass: '',
                                cssId: ''
                            },
                            children: []
                        };
                    })
                };

                this.layout[ci].columns[coli].children.push(nestedRow);
                this.showElementModal = false;
            },

            insertElement(type, name) {
                const ts = Date.now();
                const ci = this.elementContext.ci;
                const coli = this.elementContext.coli;
                
                if (ci === null || coli === null) return;

                const element = {
                    id: 'el_' + ts,
                    type: type,
                    name: name,
                    settings: {
                        ...(type === 'heading' ? { title: 'Insert Your Heading Here', tag: 'h2', color: '#1a1a1a', fontSize: '32', textAlign: 'left', fontWeight: '800' } : {}),
                        ...(type === 'text' ? { content: 'Insert your text content here. This is a modular text block that you can edit anytime.', color: '#4b5563', fontSize: '16', textAlign: 'left', lineHeight: '1.6' } : {}),
                    }
                };

                this.layout[ci].columns[coli].children.push(element);
                this.showElementModal = false;
            },

            addContainer(preset){
                const ts = Date.now();
                const newCols = preset.boxes.map((f,i)=>{
                    const fr = preset.fracs[i].split('/');
                    return {
                        id:'col_'+ts+'_'+i,
                        flex:f,
                        frac:preset.fracs[i],
                        basis: (parseInt(fr[0])/parseInt(fr[1])) * 100,
                        settings:{ 
                            marginTop:0, marginBottom:0, paddingTop:10, paddingBottom:10, paddingLeft:4, paddingRight:4,
                            align: 'flex-start',
                            contentLayout: 'column', 
                            contentAlign: 'flex-start',
                            contentVAlign: 'stretch',
                            htmlTag: 'div',
                            linkUrl: '',
                            cssClass: '',
                            cssId: ''
                        },
                        children:[]
                    };
                });

                // 1. ADD / INSERT Into Existing Container (Fix: Don't replace, just add)
                if (!this.isInserting && this.targetCi !== null) {
                    const ci = parseInt(this.targetCi);
                    if (this.layout[ci]) {
                        const updatedContainer = JSON.parse(JSON.stringify(this.layout[ci]));
                        updatedContainer.id = 'c_' + ts; 

                        const coli = this.targetColi !== null ? parseInt(this.targetColi) : -1;
                        if (coli >= 0) {
                            // Insert exactly after the clicked column
                            updatedContainer.columns.splice(coli + 1, 0, ...newCols);
                        } else {
                            // Just append to the end of the row
                            updatedContainer.columns.push(...newCols);
                        }
                        
                        this.layout.splice(ci, 1, updatedContainer);
                        
                        this.targetCi = null;
                        this.targetColi = null;
                        this.showModal = false;
                        return;
                    }
                }

                // 2. NEW CONTAINER Case (Append or Insert After)
                const newCont = JSON.parse(JSON.stringify(this.defaultContainer));
                newCont.id = 'c_' + ts;
                newCont.columns = newCols;

                if(this.targetCi !== null){
                    const ci = parseInt(this.targetCi);
                    this.layout.splice(ci + 1, 0, newCont);
                } else {
                    this.layout.push(newCont);
                }
                
                this.targetCi = null;
                this.targetColi = null;
                this.isInserting = false;
                this.showModal = false;
            },

            cloneContainer(ci){
                let c = JSON.parse(JSON.stringify(this.layout[ci]));
                c.id = Date.now();
                this.layout.splice(ci+1,0,c);
            },

            cloneColumn(ci, coli){
                // Optional: Hard limit of 6 columns per row if they wrap
                // But let's allow cloning and let flex-wrap handle it
                let col = JSON.parse(JSON.stringify(this.layout[ci].columns[coli]));
                col.id = Date.now() + Math.random();
                this.layout[ci].columns.splice(coli+1, 0, col);
            },

            deleteColumn(ci, coli){
                this.layout[ci].columns.splice(coli, 1);
            },

            startSidebarDrag(e){
                this.sidebarDrag = true;
                this.sidebarDragStartX = e.clientX;
                this.sidebarDragStartWidth = this.sidebarWidth;
                document.body.style.userSelect='none';
                document.body.style.cursor='col-resize';
            },

            dragStart(e, ci, type, side, coli = null){
                // Automatically select on drag start
                if(coli !== null) {
                    this.activeColi = coli;
                    this.activeColCi = ci;
                    this.activeCi = null;
                } else {
                    this.activeCi = ci;
                    this.activeColi = null;
                    this.activeColCi = null;
                }

                const key = type + side.charAt(0).toUpperCase()+side.slice(1);
                const startVal = coli !== null 
                    ? (this.layout[ci].columns[coli].settings ? parseFloat(this.layout[ci].columns[coli].settings[key] || 0) : 0)
                    : parseFloat(this.layout[ci].settings[key] || 0);

                this.drag = { ci, coli, type, side, key, startY:e.clientY, startX:e.clientX, startVal };
                
                const lbl = document.getElementById('drag-label');
                lbl.style.display = 'block';
                lbl.style.background = type==='margin'?'#9b59b6':'#2ea2cc';
                document.body.style.userSelect='none';
                document.body.style.cursor=(side==='left' || side==='right') ? 'ew-resize' : 'ns-resize';
            },

            onMouseMove(e){
                // Sidebar resize
                if(this.sidebarDrag){
                    const delta = e.clientX - this.sidebarDragStartX;
                    this.sidebarWidth = Math.min(500, Math.max(330, this.sidebarDragStartWidth + delta));
                }
                // Container spacing bars
                if(!this.drag) return;

                newVal = this.drag.startVal;
                const dx = e.clientX - this.drag.startX;
                const dy = e.clientY - this.drag.startY;

                // Determine if we should use percentages (% - for Column Side Margins)
                const isColSideMargin = (this.drag.coli !== null && this.drag.type === 'margin' && (this.drag.side === 'left' || this.drag.side === 'right'));

                if(this.drag.side === 'top' || this.drag.side === 'bottom'){
                    const factor = 1;
                    // For bottom side, dragging down (+dy) increases value. 
                    // For top side, dragging down (+dy) increases value if it's padding, 
                    // or increases marginTop if it's margin.
                    // Actually, +dy always increases newVal for our handles.
                    newVal = Math.max(0, this.drag.startVal + dy);
                } else if(this.drag.side === 'left' || this.drag.side === 'right'){
                    const factor = isColSideMargin ? 0.1 : 1;
                    const delta = (this.drag.side === 'left') ? dx : -dx;
                    newVal = Math.max(0, this.drag.startVal + (delta * factor));
                }

                if(isColSideMargin){
                    newVal = parseFloat(newVal.toFixed(1));
                } else {
                    newVal = Math.round(newVal);
                }

                if(this.drag.coli !== null){
                    // Update layout
                    let col = this.layout[this.drag.ci].columns[this.drag.coli];
                    if(!col.settings) col.settings = { marginTop:0, marginBottom:0, paddingTop:0, paddingBottom:0, paddingLeft:0, paddingRight:0 };
                    col.settings[this.drag.key] = newVal;
                } else {
                    this.layout[this.drag.ci].settings[this.drag.key] = newVal;
                }

                const lbl = document.getElementById('drag-label');
                lbl.style.left = (e.clientX+14)+'px';
                lbl.style.top  = (e.clientY-8)+'px';
                lbl.textContent = newVal + ( isColSideMargin ? '%' : 'px' );
            },

            onMouseUp(){
                if(this.sidebarDrag){
                    this.sidebarDrag = false;
                    document.body.style.cursor='default';
                    document.body.style.userSelect='auto';
                }
                if(!this.drag) return;
                this.drag = null;
                document.getElementById('drag-label').style.display='none';
                document.body.style.userSelect='auto';
                document.body.style.cursor='default';
            }
        }
    }
    </script>
</body>
</html>
