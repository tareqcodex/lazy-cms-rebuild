<div v-if="el.type === 'row'" class="nested-row-outer-wrapper w-full basis-full shrink-0 relative py-8 px-4 bg-slate-50/20 border border-slate-100 rounded-lg mb-10 mt-6 group/nrow shadow-sm"
     @mouseenter="setHover('nested-row', ci, coli, eli)"
     @mouseleave="setHover(null)">
    <!-- Header/Label for the nested row container -->
    <div v-if="!isPreview" class="absolute top-2 left-2 bg-[#ff9800] text-white text-[8px] px-2 py-0.5 rounded shadow-sm z-[10] font-bold uppercase tracking-wider">Row</div>
    
    <!-- Row Toolbar (Horizontal Top-Right, Premium Red) -->
    <div v-if="!isPreview" class="absolute top-0 right-0 transition-all z-[100] p-1"
         :class="(hoveredType === 'nested-row' && hoveredCi === ci && hoveredEli === eli) ? 'opacity-100' : 'opacity-0'">
        <div class="bg-[#f44336] flex items-center rounded shadow-xl h-7 px-1.5 gap-0.5 pointer-events-auto">
            <!-- Drag Row -->
            <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-move relative group/etool" 
                 draggable="true" @dragstart="onDragStart($event, 'element', ci, coli, eli)" @dragend="onDragEnd">
                <i class="fa fa-arrows-alt text-white text-[10px]"></i>
                <div class="lazy-tooltip-v2">Drag Row</div>
            </div>
            
            <!-- Edit Row -->
            <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                 @click.stop="editingContext={type:'nested-row', ci:ci, coli:coli, eli:eli}; activeTab='settings'">
                <i class="fa fa-pen text-white text-[10px]"></i>
                <div class="lazy-tooltip-v2">Edit Row</div>
            </div>

            <!-- Duplicate Row -->
            <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                 @click.stop="duplicateNestedRow(ci, coli, eli)">
                <i class="fa fa-copy text-white text-[10px]"></i>
                <div class="lazy-tooltip-v2">Duplicate</div>
            </div>

            <!-- Add (Opens Modal) -->
            <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                 @click.stop="openElementModal(ci, coli, 'nested', false, eli)">
                <i class="fa fa-plus text-white text-[10px]"></i>
                <div class="lazy-tooltip-v2">Add Nested</div>
            </div>

            <!-- Delete Row -->
            <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool text-white hover:text-red-100" 
                 @click.stop="column.elements.splice(eli, 1)">
                <i class="fa fa-trash-alt text-[10px]"></i>
                <div class="lazy-tooltip-v2">Delete Row</div>
            </div>
        </div>
    </div>

    <div :style="containerInnerStyle(el)" class="w-full relative min-h-[80px]">
        <div v-for="(ncol, ncoli) in el.columns" 
             class="column-outer relative"
             :class="[(ncol.settings.hoverType && ncol.settings.hoverType !== 'none') ? 'hover-effect-' + ncol.settings.hoverType : '', getVisibilityClasses(ncol.settings)]"
             :style="columnOuterStyle(ncol, el.columns.length)">
             
            <!-- Nested Column Inner (Handles Background, Padding, Border, Shadow) -->
            <div class="column-inner group/ncol-inner relative h-full min-h-full"
                 :class="[
                    activeColi === ncoli && activeColCi === eli ? 'nested-column-active' : '',
                    isDragging && dragCi === ci && dragColi === coli && dragEli === eli && dragNcoli === ncoli ? 'dragging-no-transition' : '',
                    dragTarget === 'nested-column-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-null' && dragPosition === 'left' ? 'border-l-4 border-l-blue-500' : '',
                    dragTarget === 'nested-column-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-null' && dragPosition === 'right' ? 'border-r-4 border-r-blue-500' : '',
                    (dragTarget === 'nested-column-' + ci + '-' + coli + '-' + eli + '-' + ncoli + '-null' && dragSource?.type === 'element') ? 'ring-2 ring-blue-400 ring-inset bg-blue-50/30' : '',
                    ncol.settings.linkUrl ? 'cursor-pointer' : ''
                 ]"
                 :style="columnInnerStyle(ncol)"
                 @click.stop="activeColi = ncoli; activeColCi = eli; editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}"
                 @mouseenter="setHover('nested-column', ci, coli, eli, ncoli)"
                 @mouseleave="setHover(null)"
                 @dragover="onDragOver($event, 'nested-column', ci, coli, eli, ncoli)"
                 @drop="onDrop($event, 'nested-column', ci, coli, eli, ncoli)">

                <!-- Nested Column Toolbar (Horizontal Top-Left, Premium Orange) -->
                <div class="absolute top-0 left-0 transition-opacity z-[1000] hover:z-[1100] p-1" v-if="!isPreview"
                     :class="(hoveredType === 'nested-column' && hoveredCi === ci && hoveredColi === coli && hoveredEli === eli && hoveredNcoli === ncoli) ? 'opacity-100' : 'opacity-0'">
                    <div class="bg-[#ff9800] flex items-center rounded shadow-xl h-7 px-1.5 gap-0.5 pointer-events-auto">
                        <!-- Drag -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-move relative group/etool" 
                             draggable="true" @dragstart="onDragStart($event, 'nested-column', ci, coli, eli, ncoli)" @dragend="onDragEnd">
                            <i class="fa fa-arrows-alt text-white text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Drag Column</div>
                        </div>
                        
                        <!-- Duplicate -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                             @click.stop="duplicateNestedColumn(ci, coli, eli, ncoli)">
                            <i class="fa fa-copy text-white text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Duplicate</div>
                        </div>

                        <!-- Edit -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                             @click.stop="editingContext={type:'nested-column', ci:ci, coli:coli, eli:eli, ncoli:ncoli}; activeTab='settings'">
                            <i class="fa fa-pen text-white text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Column Settings</div>
                        </div>

                        <!-- Add Nested / Element -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                             @click.stop="openElementModal(ci, coli, 'nested', false, eli, ncoli, null, ['nested'])">
                            <i class="fa fa-plus text-white text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Add Content</div>
                        </div>

                        <!-- Save -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                             @click.stop="saveLayout">
                            <i class="fa fa-hdd text-white text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Save</div>
                        </div>

                        <!-- Delete -->
                        <div class="w-6 h-6 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool text-white hover:text-red-200" 
                             @click.stop="el.columns.splice(ncoli, 1)">
                            <i class="fa fa-trash-alt text-[10px]"></i>
                            <div class="lazy-tooltip-v2">Delete</div>
                        </div>
                    </div>
                </div>

                <!-- Overlays -->
                <div v-if="!isPreview" class="absolute inset-0 pointer-events-none z-0">
                    <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#ff9800]/5 transition-opacity"
                         :style="{ height: (ncol.settings.paddingTop || 0) + 'px', top: '0px' }"
                         :class="shouldShowGuide('nested-column', ci, coli, eli, ncoli) ? ( (isDragging && dragType === 'paddingTop' && dragNcoli === ncoli) || (hoveredType === 'nested-column' && hoveredCi === ci && hoveredColi === coli && hoveredEli === eli && hoveredNcoli === ncoli) ? 'opacity-100' : 'opacity-0' ) : 'hidden'">
                         <div class="absolute bottom-0 left-0 w-full border-b border-dashed border-[#ff9800]/30"></div>
                    </div>
                    <div class="absolute left-0 right-0 pointer-events-none z-0 bg-[#ff9800]/5 transition-opacity"
                         :style="{ height: (ncol.settings.paddingBottom || 0) + 'px', bottom: '0px' }"
                         :class="shouldShowGuide('nested-column', ci, coli, eli, ncoli) ? ( (isDragging && dragType === 'paddingBottom' && dragNcoli === ncoli) || (hoveredType === 'nested-column' && hoveredCi === ci && hoveredColi === coli && hoveredEli === eli && hoveredNcoli === ncoli) ? 'opacity-100' : 'opacity-0' ) : 'hidden'">
                         <div class="absolute top-0 left-0 w-full border-t border-dashed border-[#ff9800]/30"></div>
                    </div>
                </div>

                <!-- Nested Column Handles -->
                <div v-if="!isPreview" class="absolute inset-0 pointer-events-none z-[600] transition-opacity"
                     :class="shouldShowGuide('nested-column', ci, coli, eli, ncoli) ? ( ((activeColi === ncoli && activeColCi === eli) || (isDragging && dragNcoli === ncoli) || (hoveredType === 'nested-column' && hoveredCi === ci && hoveredColi === coli && hoveredEli === eli && hoveredNcoli === ncoli)) ? 'opacity-100' : 'opacity-0' ) : 'hidden'">
                    
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

                    <div class="absolute bottom-0.5 left-1/2 -translate-x-1/2 pointer-events-auto">
                        <div class="handle-orange group/nh"
                             :class="isDragging ? '' : 'transition-all'"
                             @mousedown.stop.prevent="startDrag($event, 'paddingBottom', ci, coli, eli, ncoli)">
                            <i class="fa fa-bars"></i>
                            <div class="lazy-tooltip-v2 !opacity-100 !visible" v-if="isDragging && dragType === 'paddingBottom' && dragNcoli === ncoli">@{{ ncol.settings.paddingBottom || 0 }}px</div>
                            <div class="lazy-tooltip-v2 opacity-0 group-hover/nh:opacity-100" v-else>@{{ ncol.settings.paddingBottom || 0 }}px</div>
                        </div>
                    </div>
                </div>

                <div v-if="!isPreview && ncol.elements.length === 0" class="text-center w-full flex flex-col items-center py-10">
                    <button @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli)" class="w-8 h-8 bg-[#ff9800] text-white rounded shadow-lg flex items-center justify-center hover:scale-110 transition-all relative group/nadd pointer-events-auto">
                        <i class="fa fa-plus text-base pointer-events-none"></i>
                        <div class="lazy-tooltip-v2 !bottom-auto !top-full !mt-2 opacity-0 group-hover/nadd:opacity-100">Add Element</div>
                    </button>
                </div>
                
                <div v-for="(nestedEl, nestedEli) in ncol.elements" :key="nestedEl.id" 
                     class="relative group/nel"
                     :class="[
                        (ncol.settings.contentLayout === 'row' && nestedEl.type !== 'row') ? '' : 'w-full',
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

                        <!-- Nested Element Toolbar -->
                        <div class="absolute top-0 left-0 w-full flex justify-center opacity-0 group-hover/nel:opacity-100 transition-all duration-200 z-[1010] hover:z-[1100] pointer-events-none p-0.5" v-if="!isPreview">
                            <div class="flex items-center bg-[#9c27b0] text-white rounded shadow-xl h-6 px-1 gap-0.5 pointer-events-auto">
                                <div class="w-5 h-5 flex items-center justify-center hover:bg-white/20 rounded cursor-move relative group/etool" 
                                     draggable="true" @dragstart="onDragStart($event, 'element', ci, coli, eli, ncoli, nestedEli)" @dragend="onDragEnd">
                                    <i class="fa fa-arrows-alt text-[8px]"></i>
                                    <div class="lazy-tooltip-v2 opacity-0 group-hover/etool:opacity-100 z-[100] whitespace-nowrap">Move</div>
                                </div>
                                <div class="w-5 h-5 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                                     @click.stop="duplicateNestedElement(ci, coli, eli, ncoli, nestedEli)">
                                    <i class="fa fa-copy text-[8px]"></i>
                                    <div class="lazy-tooltip-v2 opacity-0 group-hover/etool:opacity-100 z-[100] whitespace-nowrap">Duplicate</div>
                                </div>
                                <div class="w-5 h-5 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                                     @click.stop="editingContext={type:'element', ci:ci, coli:coli, eli:eli, ncoli:ncoli, neli:nestedEli}; activeTab='settings'">
                                    <i class="fa fa-pen text-[8px]"></i>
                                    <div class="lazy-tooltip-v2 opacity-0 group-hover/etool:opacity-100 z-[100] whitespace-nowrap">Edit</div>
                                </div>
                                <div class="w-5 h-5 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool" 
                                     @click.stop="openElementModal(ci, coli, 'design', true, eli, ncoli, nestedEli + 1, ['design'])">
                                    <i class="fa fa-plus text-[8px]"></i>
                                    <div class="lazy-tooltip-v2 opacity-0 group-hover/etool:opacity-100 z-[100] whitespace-nowrap text-[8px]">Add Below</div>
                                </div>
                                <div class="w-5 h-5 flex items-center justify-center hover:bg-white/20 rounded cursor-pointer relative group/etool text-white hover:text-red-200" 
                                     @click.stop="ncol.elements.splice(nestedEli, 1)">
                                    <i class="fa fa-trash-alt text-[8px]"></i>
                                    <div class="lazy-tooltip-v2 opacity-0 group-hover/etool:opacity-100 z-[100] whitespace-nowrap">Delete</div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
