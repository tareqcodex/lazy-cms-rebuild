<!-- Columns Loop -->
<div v-for="(column, coli) in container.columns" :key="column.id"
     class="column-outer relative"
     :style="columnOuterStyle(column, container.columns.length)">

    
    <div class="column-inner group/col relative h-full min-h-full"
         :class="[
            activeColi === coli && activeColCi === ci ? 'column-active' : '', 
            isDragging && dragCi === ci && dragColi === coli ? 'dragging-no-transition' : '',
            dragTarget === 'column-' + ci + '-' + coli + '-null-null-null' && dragPosition === 'left' ? 'border-l-4 border-l-blue-500' : '',
            dragTarget === 'column-' + ci + '-' + coli + '-null-null-null' && dragPosition === 'right' ? 'border-r-4 border-r-blue-500' : ''
         ]"
         :style="columnInnerStyle(column)"
         @click.stop="activeColi = coli; activeColCi = ci; editingContext={type:'column', ci:ci, coli:coli}"
         @dragover="onDragOver($event, 'column', ci, coli)"
         @drop="onDrop($event, 'column', ci, coli)">

        <!-- Column Toolbar (Top Left) -->
        <div class="column-left-panel transition-opacity" v-if="!isPreview"
             :class="(activeColi === coli && activeColCi === ci) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
            <div class="panel-inner shadow-xl group/panel">
                <div class="panel-btn" @click.stop="editingCi = ci; activeColi = coli; activeColCi = ci; editingContext={type:'column', ci:ci, coli:coli}; activeTab='settings'">
                    <i class="fa fa-pen"></i><div class="lazy-tooltip">Column Options</div>
                </div>
                <div class="panel-btn" @click.stop="openColumnModal(ci, 'edit')">
                    <i class="fa fa-plus-square"></i><div class="lazy-tooltip">Add Column</div>
                </div>
                
                <div class="flex items-center overflow-hidden max-w-0 opacity-0 group-hover/panel:max-w-[200px] group-hover/panel:opacity-100 transition-all duration-300">
                    <div class="column-label whitespace-nowrap">@{{ formatBasisToFraction(column.basis) }}</div>
                    <div class="panel-btn" @click.stop="duplicateColumn(ci, coli)"><i class="fa fa-copy"></i><div class="lazy-tooltip">Duplicate</div></div>
                    <div class="panel-btn"><i class="fa fa-hdd"></i><div class="lazy-tooltip">Save</div></div>
                    <div class="panel-btn" @click.stop="container.columns.splice(coli, 1)"><i class="fa fa-trash-alt"></i><div class="lazy-tooltip">Delete</div></div>
                    <div class="panel-btn cursor-move" draggable="true" @dragstart="onDragStart($event, 'column', ci, coli)" @dragend="onDragEnd"><i class="fa fa-arrows-alt"></i><div class="lazy-tooltip">Drag</div></div>
                </div>
            </div>
        </div>

        <!-- Column Overlays -->
        <div v-if="!isPreview">
            <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#9c27b0]/5 transition-opacity"
                 :style="{ height: (column.settings.marginTop || 0) + 'px', top: '-' + (column.settings.marginTop || 0) + 'px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'marginTop') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 left-0 w-full border-t border-dashed border-[#9c27b0]/20"></div>
            </div>
            <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#9c27b0]/5 transition-opacity"
                 :style="{ height: (column.settings.marginBottom || 0) + 'px', bottom: '-' + (column.settings.marginBottom || 0) + 'px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'marginBottom') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute bottom-0 left-0 w-full border-b border-dashed border-[#9c27b0]/20"></div>
            </div>
            <div class="absolute top-0 bottom-0 pointer-events-none z-0 bg-[#9c27b0]/5 transition-opacity"
                 :style="{ width: (column.settings.marginLeft || 0) + 'px', left: '-' + (column.settings.marginLeft || 0) + 'px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'marginLeft') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 left-0 h-full border-l border-dashed border-[#9c27b0]/20"></div>
            </div>
            <div class="absolute top-0 bottom-0 pointer-events-none z-0 bg-[#9c27b0]/5 transition-opacity"
                 :style="{ width: (column.settings.marginRight || 0) + 'px', right: '-' + (column.settings.marginRight || 0) + 'px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'marginRight') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 right-0 h-full border-r border-dashed border-[#9c27b0]/20"></div>
            </div>
            <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#0091ea]/5 transition-opacity"
                 :style="{ height: (column.settings.paddingTop || 0) + 'px', top: '0px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'paddingTop') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute bottom-0 left-0 w-full border-b border-dashed border-[#0091ea]/20"></div>
            </div>
            <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#0091ea]/5 transition-opacity"
                 :style="{ height: (column.settings.paddingBottom || 0) + 'px', bottom: '0px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'paddingBottom') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 left-0 w-full border-t border-dashed border-[#0091ea]/20"></div>
            </div>
            <div class="absolute top-0 bottom-0 pointer-events-none z-0 bg-[#0091ea]/5 transition-opacity"
                 :style="{ width: (column.settings.paddingLeft || 0) + 'px', left: '0px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'paddingLeft') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 right-0 h-full border-r border-dashed border-[#0091ea]/20"></div>
            </div>
            <div class="absolute top-0 bottom-0 pointer-events-none z-0 bg-[#0091ea]/5 transition-opacity"
                 :style="{ width: (column.settings.paddingRight || 0) + 'px', right: '0px' }"
                 :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli && dragType === 'paddingRight') ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
                 <div class="absolute top-0 left-0 h-full border-l border-dashed border-[#0091ea]/20"></div>
            </div>
        </div>

        <!-- Column Handles -->
        <div v-if="!isPreview" class="absolute inset-0 pointer-events-none z-[200] transition-opacity"
             :class="( (activeColi === coli && activeColCi === ci) || (isDragging && dragCi === ci && dragColi === coli) ) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
            
            <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-auto flex gap-0.5 items-start">
                <div class="handle-purple group/chmt" @mousedown.stop.prevent="startDrag($event, 'marginTop', ci, coli)">
                    <i class="fa fa-bars"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chmt:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'marginTop' && dragCi === ci && dragColi === coli}">@{{ column.settings.marginTop || 0 }}px</div>
                </div>
                <div class="handle-blue group/chpt" :class="isDragging ? '' : 'transition-all'" :style="{ transform: 'translateY(' + (column.settings.paddingTop || 0) + 'px)' }" @mousedown.stop.prevent="startDrag($event, 'paddingTop', ci, coli)">
                    <i class="fa fa-bars"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chpt:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'paddingTop' && dragCi === ci && dragColi === coli}">@{{ column.settings.paddingTop || 0 }}px</div>
                </div>
            </div>

            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 pointer-events-auto flex gap-0.5 items-end">
                <div class="handle-purple group/chmb" :class="isDragging ? '' : 'transition-all'" :style="{ transform: 'translateY(' + (column.settings.marginBottom || 0) + 'px)' }" @mousedown.stop.prevent="startDrag($event, 'marginBottom', ci, coli)">
                    <i class="fa fa-bars"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chmb:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'marginBottom' && dragCi === ci && dragColi === coli}">@{{ column.settings.marginBottom || 0 }}px</div>
                </div>
                <div class="handle-blue group/chpb" @mousedown.stop.prevent="startDrag($event, 'paddingBottom', ci, coli)">
                    <i class="fa fa-bars"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chpb:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'paddingBottom' && dragCi === ci && dragColi === coli}">@{{ column.settings.paddingBottom || 0 }}px</div>
                </div>
            </div>

            <div class="absolute left-0 top-1/2 -translate-y-1/2 pointer-events-auto flex flex-col gap-0.5 items-start">
                <div class="handle-purple-h group/chml" @mousedown.stop.prevent="startDrag($event, 'marginLeft', ci, coli)">
                    <i class="fa fa-bars" style="transform: rotate(90deg);"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chml:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'marginLeft' && dragCi === ci && dragColi === coli}">@{{ column.settings.marginLeft || 0 }}px</div>
                </div>
                <div class="handle-blue-h group/chpl" :class="isDragging ? '' : 'transition-all'" :style="{ transform: 'translateX(' + (column.settings.paddingLeft || 0) + 'px)' }" @mousedown.stop.prevent="startDrag($event, 'paddingLeft', ci, coli)">
                    <i class="fa fa-bars" style="transform: rotate(90deg);"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chpl:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'paddingLeft' && dragCi === ci && dragColi === coli}">@{{ column.settings.paddingLeft || 0 }}px</div>
                </div>
            </div>

            <div class="absolute right-0 top-1/2 -translate-y-1/2 pointer-events-auto flex flex-col gap-0.5 items-end">
                <div class="handle-purple-h group/chmr" @mousedown.stop.prevent="startDrag($event, 'marginRight', ci, coli)">
                    <i class="fa fa-bars" style="transform: rotate(90deg);"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chmr:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'marginRight' && dragCi === ci && dragColi === coli}">@{{ column.settings.marginRight || 0 }}px</div>
                </div>
                <div class="handle-blue-h group/chpr" :class="isDragging ? '' : 'transition-all'" :style="{ transform: 'translateX(-' + (column.settings.paddingRight || 0) + 'px)' }" @mousedown.stop.prevent="startDrag($event, 'paddingRight', ci, coli)">
                    <i class="fa fa-bars" style="transform: rotate(90deg);"></i>
                    <div class="lazy-tooltip opacity-0 group-hover/chpr:opacity-100" :class="{'opacity-100!': isDragging && dragType === 'paddingRight' && dragCi === ci && dragColi === coli}">@{{ column.settings.paddingRight || 0 }}px</div>
                </div>
            </div>
        </div>

        <!-- Add Element Button: CENTERED if empty -->
        <div v-if="!isPreview && column.elements.length === 0" 
             class="absolute inset-0 flex items-center justify-center z-10 transition-opacity"
             :class="((activeColCi === ci && activeColi === coli) || (isDragging && dragCi === ci && dragColi === coli)) ? 'opacity-100' : 'opacity-0 group-hover/col:opacity-100'">
            <button @click.stop="openElementModal(ci, coli, 'design')" 
                    class="w-8 h-8 bg-[#0091ea] text-white rounded shadow-lg flex items-center justify-center hover:scale-110 transition-all relative group/coladdbtn pointer-events-auto">
                <i class="fa fa-plus text-base pointer-events-none"></i>
                <div class="lazy-tooltip opacity-0 group-hover/coladdbtn:opacity-100" style="top: 100%; margin-top: 10px; display: block !important;">Add Element</div>
            </button>
        </div>
        
        <div v-for="(el, eli) in column.elements" :key="el.id" 
             class="w-full relative group/el"
             :class="[
                dragTarget === 'element-' + ci + '-' + coli + '-' + eli + '-null-null' && dragPosition === 'top' ? 'border-t-2 border-t-blue-500' : '',
                dragTarget === 'element-' + ci + '-' + coli + '-' + eli + '-null-null' && dragPosition === 'bottom' ? 'border-b-2 border-b-blue-500' : ''
             ]"
             @dragover="onDragOver($event, 'element', ci, coli, eli)"
             @drop="onDrop($event, 'element', ci, coli, eli)">
            
            @include('cms-dashboard::admin.lazy-builder.partials.components.elements.heading')
            @include('cms-dashboard::admin.lazy-builder.partials.components.elements.text')
            @include('cms-dashboard::admin.lazy-builder.partials.components.nested.row')

            <div class="absolute -top-4 right-0 opacity-0 group-hover/el:opacity-100 transition-opacity z-[500]" v-if="!isPreview">
                <div class="flex rounded-sm overflow-hidden shadow-sm h-5" :class="el.type === 'row' ? 'bg-slate-800' : 'bg-[#0091ea]'">
                    <button v-if="el.type !== 'row'" @click.stop="editingContext={type:'element', ci:ci, coli:coli, eli:eli, ncoli:null, neli:null}; activeTab='settings'" class="px-1 hover:bg-black/20 text-white text-[8px]"><i class="fa fa-pen"></i></button>
                    <button @click.stop="column.elements.splice(eli, 1)" class="px-1 hover:bg-black/20 text-white text-[8px]"><i class="fa fa-trash-alt"></i></button>
                    <button class="px-1 hover:bg-black/20 text-white text-[8px] cursor-move" draggable="true" @dragstart="onDragStart($event, 'element', ci, coli, eli)" @dragend="onDragEnd"><i class="fa fa-arrows-alt"></i></button>
                </div>
            </div>
        </div>

        <!-- Add Element Button: BOTTOM if not empty -->
        <div v-if="!isPreview && column.elements.length > 0" 
             class="flex justify-center py-4 opacity-0 group-hover/col:opacity-100 transition-opacity">
            <button @click.stop="openElementModal(ci, coli, 'design')" 
                    class="w-8 h-8 bg-[#0091ea] text-white rounded shadow-lg flex items-center justify-center hover:scale-110 transition-all relative group/coladdbtn2">
                <i class="fa fa-plus text-base"></i>
                <div class="lazy-tooltip opacity-0 group-hover/coladdbtn2:opacity-100" style="top: 100%; margin-top: 10px; display: block !important;">Add Element</div>
            </button>
        </div>
    </div>
</div>
