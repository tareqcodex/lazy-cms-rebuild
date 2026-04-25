<div v-if="el.type === 'row'" class="nested-row-wrapper mt-4 mb-4 relative group/nrow">
    
    <div class="flex flex-wrap w-full relative" style="gap: 2%;">
        <div v-for="(ncol, ncoli) in el.columns" 
             class="relative flex flex-col transition-all group/ncol"
             :class="[
                activeColi === ncoli && activeColCi === eli ? 'nested-column-active' : '',
                dragTarget === 'nested-column-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-null' && dragPosition === 'left' ? 'border-l-4 border-l-blue-500' : '',
                dragTarget === 'nested-column-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-null' && dragPosition === 'right' ? 'border-r-4 border-r-blue-500' : ''
             ]"
             :style="{ 
                flex: `0 0 ${ncol.basis >= 100 ? '100%' : `calc(${ncol.basis}% - 2%)`}`, 
                maxWidth: ncol.basis >= 100 ? '100%' : `calc(${ncol.basis}% - 2%)`,
                paddingTop: (ncol.settings.paddingTop || 0) + 'px',
                paddingBottom: (ncol.settings.paddingBottom || 0) + 'px',
                paddingLeft: (ncol.settings.paddingLeft || 0) + 'px',
                paddingRight: (ncol.settings.paddingRight || 0) + 'px',
                marginTop: (ncol.settings.marginTop || 0) + 'px',
                marginBottom: (ncol.settings.marginBottom || 0) + 'px',
                marginLeft: (ncol.settings.marginLeft || 0) + 'px',
                marginRight: (ncol.settings.marginRight || 0) + 'px',
                minHeight: (100 + (ncol.settings.paddingTop || 0) + (ncol.settings.paddingBottom || 0)) + 'px'
             }"
             @click.stop="activeColi = ncoli; activeColCi = eli; editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}"
             @dragover="onDragOver($event, 'nested-column', ci, coli, eli, ncoli)"
             @drop="onDrop($event, 'nested-column', ci, coli, eli, ncoli)">
            
            <!-- Orange Dashed Border (boundary of the column) -->
            <div v-if="!isPreview" class="absolute inset-0 border border-dashed border-[#ff9800]/60 pointer-events-none rounded-sm z-0"></div>

            <!-- Nested Column Toolbar (Top Left, Orange) - Fixed Z-Index -->
            <div class="absolute top-0 left-0 opacity-0 group-hover/ncol:opacity-100 transition-opacity z-[500] p-1" v-if="!isPreview">
                <div class="bg-[#ff9800] flex items-center rounded shadow-lg h-7 px-1 relative group/panel">
                    <div class="panel-btn-orange group/pbt" @click.stop="editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}; activeTab='settings'">
                        <i class="fa fa-pen"></i>
                        <div class="lazy-tooltip-v2">Column Options</div>
                    </div>
                    <div class="panel-btn-orange group/pbt" @click.stop="openElementModal(ci, coli, 'nested', false, eli)">
                        <i class="fa fa-plus-square text-white text-[10px]"></i>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/pbt:opacity-100">Add Column</div>
                    </div>
                    
                    <div class="flex items-center overflow-hidden max-w-0 opacity-0 group-hover/panel:max-w-[200px] group-hover/panel:opacity-100 transition-all duration-300">
                        <div class="px-2 text-[10px] font-bold text-white border-r border-white/10 h-4 flex items-center whitespace-nowrap">@{{ formatBasisToFraction(ncol.basis) }}</div>
                        <div class="panel-btn-orange group/pbt" @click.stop="duplicateNestedColumn(ci, coli, eli, ncoli)">
                            <i class="fa fa-copy"></i>
                            <div class="lazy-tooltip-v2">Duplicate</div>
                        </div>
                        <div class="panel-btn-orange group/pbt">
                            <i class="fa fa-hdd"></i>
                            <div class="lazy-tooltip-v2">Save</div>
                        </div>
                        <div class="panel-btn-orange group/pbt" @click.stop="el.columns.splice(ncoli, 1)">
                            <i class="fa fa-trash-alt"></i>
                            <div class="lazy-tooltip-v2">Delete</div>
                        </div>
                        <div class="panel-btn-orange group/pbt cursor-move" draggable="true" @dragstart="onDragStart($event, 'nested-column', ci, coli, eli, ncoli)" @dragend="onDragEnd">
                            <i class="fa fa-arrows-alt"></i>
                            <div class="lazy-tooltip-v2">Drag</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overlays -->
            <div v-if="!isPreview">
                <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#ff9800]/5 transition-opacity"
                     :style="{ height: (ncol.settings.paddingTop || 0) + 'px', top: '0px' }"
                     :class="(isDragging && dragType === 'paddingTop' && dragNcoli === ncoli) ? 'opacity-100' : 'opacity-0 group-hover/ncol:opacity-100'">
                     <div class="absolute bottom-0 left-0 w-full border-b border-dashed border-[#ff9800]/30"></div>
                </div>
                <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#ff9800]/5 transition-opacity"
                     :style="{ height: (ncol.settings.paddingBottom || 0) + 'px', bottom: '0px' }"
                     :class="(isDragging && dragType === 'paddingBottom' && dragNcoli === ncoli) ? 'opacity-100' : 'opacity-0 group-hover/ncol:opacity-100'">
                     <div class="absolute top-0 left-0 w-full border-t border-dashed border-[#ff9800]/30"></div>
                </div>
            </div>

            <!-- Nested Column Handles (IDENTICAL TO PARENT COLUMN LOGIC) -->
            <div v-if="!isPreview" class="absolute inset-0 pointer-events-none z-[600] transition-opacity"
                 :class="( (activeColi === ncoli && activeColCi === eli) || (isDragging && dragNcoli === ncoli) ) ? 'opacity-100' : 'opacity-0 group-hover/ncol:opacity-100'">
                
                <!-- Padding Top Handle (Moves content down) -->
                <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-auto">
                    <div class="handle-orange group/nh" 
                         :class="isDragging ? '' : 'transition-all'"
                         :style="{ transform: 'translateY(' + (ncol.settings.paddingTop || 0) + 'px)' }"
                         @mousedown.stop.prevent="startDrag($event, 'paddingTop', ci, coli, eli, ncoli)">
                        <i class="fa fa-bars"></i>
                        <div class="lazy-tooltip-v2 !opacity-100 !visible" v-if="isDragging && dragType === 'paddingTop' && dragNcoli === ncoli">@{{ ncol.settings.paddingTop || 0 }}px</div>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/nh:opacity-100" v-else>@{{ ncol.settings.paddingTop || 0 }}px</div>
                    </div>
                </div>

                <!-- Padding Bottom Handle -->
                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 pointer-events-auto">
                    <div class="handle-orange group/nh"
                         :class="isDragging ? '' : 'transition-all'"
                         @mousedown.stop.prevent="startDrag($event, 'paddingBottom', ci, coli, eli, ncoli)">
                        <i class="fa fa-bars"></i>
                        <div class="lazy-tooltip-v2 !opacity-100 !visible" v-if="isDragging && dragType === 'paddingBottom' && dragNcoli === ncoli">@{{ ncol.settings.paddingBottom || 0 }}px</div>
                        <div class="lazy-tooltip-v2 opacity-0 group-hover/nh:opacity-100" v-else>@{{ ncol.settings.paddingBottom || 0 }}px</div>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="relative z-10 flex-1 flex flex-col items-center justify-center">
                <div v-if="!isPreview && ncol.elements.length === 0" class="text-center w-full flex flex-col items-center">
                    <button @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli)" class="w-8 h-8 bg-[#ff9800] text-white rounded shadow-lg flex items-center justify-center hover:scale-110 transition-all relative group/nadd pointer-events-auto">
                        <i class="fa fa-plus text-base pointer-events-none"></i>
                        <div class="lazy-tooltip-v2 !bottom-auto !top-full !mt-2 opacity-0 group-hover/nadd:opacity-100">Add Element</div>
                    </button>
                </div>
                
                <div v-for="(nestedEl, nestedEli) in ncol.elements" :key="nestedEl.id" 
                     class="w-full relative group/nel"
                     :class="[
                        dragTarget === 'element-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-' + nestedEli && dragPosition === 'top' ? 'border-t-2 border-t-blue-500' : '',
                        dragTarget === 'element-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-' + nestedEli && dragPosition === 'bottom' ? 'border-b-2 border-b-blue-500' : ''
                     ]"
                     @dragover="onDragOver($event, 'element', ci, coli, eli, ncoli, nestedEli)"
                     @drop="onDrop($event, 'element', ci, coli, eli, ncoli, nestedEli)">
                    
                    <div v-if="nestedEl.type === 'heading'" class="mb-2">
                        <h3 :style="{ textAlign: nestedEl.settings.textAlign }" class="m-0 text-sm font-bold">@{{ nestedEl.settings.title || 'Heading' }}</h3>
                    </div>
                    <div v-else-if="nestedEl.type === 'text'" class="mb-2">
                        <div v-html="nestedEl.settings.content || 'Text'" class="text-xs"></div>
                    </div>

                    <div class="absolute -top-4 right-0 opacity-0 group-hover/nel:opacity-100 transition-opacity z-[500]" v-if="!isPreview">
                        <div class="flex bg-[#0091ea] rounded-sm overflow-hidden shadow-sm h-5">
                            <button @click.stop="editingContext={type:'element', ci:ci, coli:coli, eli:eli, ncoli:ncoli, neli:nestedEli}; activeTab='settings'" class="px-1 hover:bg-[#007cc0] text-white text-[8px]"><i class="fa fa-pen"></i></button>
                            <button @click.stop="ncol.elements.splice(nestedEli, 1)" class="px-1 hover:bg-[#007cc0] text-white text-[8px]"><i class="fa fa-trash-alt"></i></button>
                            <button class="px-1 hover:bg-[#007cc0] text-white text-[8px] cursor-move" draggable="true" @dragstart="onDragStart($event, 'element', ci, coli, eli, ncoli, nestedEli)" @dragend="onDragEnd"><i class="fa fa-arrows-alt"></i></button>
                        </div>
                    </div>
                </div>

                <div v-if="!isPreview && ncol.elements.length > 0" class="mt-2 opacity-0 group-hover/ncol:opacity-100 transition-opacity w-full flex justify-center">
                    <button @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli)" class="w-8 h-8 bg-[#ff9800] text-white rounded shadow-lg flex items-center justify-center hover:scale-110 transition-all relative group/nadd2 pointer-events-auto">
                        <i class="fa fa-plus text-base pointer-events-none"></i>
                        <div class="lazy-tooltip-v2 !bottom-auto !top-full !mt-2 opacity-0 group-hover/nadd2:opacity-100">Add Element</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
