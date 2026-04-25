<div class="h-full flex flex-col bg-white">
    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
        <h3 class="text-[13px] font-bold text-[#444]">Container</h3>
        <div class="flex gap-2">
            <button class="text-slate-400 hover:text-slate-600"><i class="fa fa-ellipsis-h text-[10px]"></i></button>
            <button @click="editingContext.type = null" class="text-slate-400 hover:text-red-500"><i class="fa fa-times text-[10px]"></i></button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex bg-[#0091ea]">
        <button @click="activePanelTab = 'general'" :class="activePanelTab === 'general' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[12px] font-bold transition-colors">
            <i class="fa fa-sliders-h"></i>
        </button>
        <button @click="activePanelTab = 'design'" :class="activePanelTab === 'design' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[10px] font-bold transition-colors">
            Design
        </button>
        <button @click="activePanelTab = 'background'" :class="activePanelTab === 'background' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[10px] font-bold transition-colors">
            Background
        </button>
        <button @click="activePanelTab = 'link'" :class="activePanelTab === 'link' ? 'bg-[#007cc0] text-white' : 'text-white/70 hover:text-white'" class="flex-1 py-2 text-[12px] transition-colors">
            <i class="fa fa-link"></i>
        </button>
    </div>

    <!-- Content -->
    <div class="flex-1 overflow-y-auto p-4" v-if="layout[editingContext.ci]">
        
        <!-- General Tab -->
        <div v-show="activePanelTab === 'general'" class="space-y-6">
            
            <!-- Interior Content Width -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Interior Content Width</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="flex bg-slate-100 rounded overflow-hidden">
                <button @click="layout[editingContext.ci].settings.contentWidth = '100%'" 
                        :class="layout[editingContext.ci].settings.contentWidth === '100%' ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">100% Width</button>
                <button @click="layout[editingContext.ci].settings.contentWidth = 'site'" 
                        :class="layout[editingContext.ci].settings.contentWidth === 'site' ? 'bg-slate-800 text-white shadow-inner' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">Site Width</button>
            </div>
        </div>

        <!-- Height -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Height</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="layout[editingContext.ci].settings.height" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="auto">Auto</option>
                <option value="full">Full Height</option>
                <option value="custom">Custom</option>
            </select>
            <div v-if="layout[editingContext.ci].settings.height === 'custom'" class="mt-2">
                <input type="text" v-model="layout[editingContext.ci].settings.customHeight" placeholder="e.g. 400px, 50vh" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>
        </div>

        <!-- Column Alignment -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Alignment</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button @click="layout[editingContext.ci].settings.alignItems = 'flex-start'" 
                        :class="layout[editingContext.ci].settings.alignItems === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="4" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="4" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Top</div>
                </button>
                <button @click="layout[editingContext.ci].settings.alignItems = 'center'" 
                        :class="layout[editingContext.ci].settings.alignItems === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="7" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="5" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="8" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Center</div>
                </button>
                <button @click="layout[editingContext.ci].settings.alignItems = 'flex-end'" 
                        :class="layout[editingContext.ci].settings.alignItems === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="10" width="3" height="10" rx="0.5"/>
                        <rect x="10.5" y="6" width="3" height="14" rx="0.5"/>
                        <rect x="16" y="12" width="3" height="8" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Bottom</div>
                </button>
                <button @click="layout[editingContext.ci].settings.alignItems = 'stretch'" 
                        :class="layout[editingContext.ci].settings.alignItems === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3l3 3h-2v12h2l-3 3-3-3h2V6H9l3-3z" fill="currentColor"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                </button>
            </div>
        </div>

        <!-- Row Alignment (Align Content) -->
        <div v-if="layout[editingContext.ci].settings.height !== 'auto'">
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Row Alignment</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <!-- 1. Stretch (Default) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'stretch'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'stretch' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="3" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="8" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="13" width="10" height="3" rx="0.5"/>
                        <rect x="4" y="18" width="16" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Stretch</div>
                </button>

                <!-- 2. Align Top (flex-start) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'flex-start'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="8" width="10" height="4" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Top</div>
                </button>

                <!-- 3. Align Center (center) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'center'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="11" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="7" width="10" height="3" rx="0.5"/>
                        <rect x="7" y="14" width="10" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Center</div>
                </button>

                <!-- 4. Align Bottom (flex-end) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'flex-end'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="7" y="12" width="10" height="4" rx="0.5"/>
                        <rect x="5" y="18" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Align Bottom</div>
                </button>

                <!-- 5. Space Between (space-between) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'space-between'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="3" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="6" width="10" height="3" rx="0.5"/>
                        <rect x="7" y="15" width="10" height="3" rx="0.5"/>
                        <rect x="5" y="19" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                </button>

                <!-- 6. Space Around (space-around) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'space-around'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="6" width="14" height="2" rx="0.5"/>
                        <rect x="7" y="9" width="10" height="2" rx="0.5"/>
                        <rect x="7" y="13" width="10" height="2" rx="0.5"/>
                        <rect x="5" y="16" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                </button>

                <!-- 7. Space Evenly (space-evenly) -->
                <button @click="layout[editingContext.ci].settings.alignContent = 'space-evenly'" 
                        :class="layout[editingContext.ci].settings.alignContent === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="5" y="4" width="14" height="2" rx="0.5"/>
                        <rect x="5" y="11" width="14" height="2" rx="0.5"/>
                        <rect x="5" y="18" width="14" height="2" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                </button>
            </div>
        </div>

        <!-- Column Justification -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Justification</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <button @click="layout[editingContext.ci].settings.justifyContent = 'flex-start'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'flex-start' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="4" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="4" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Left</div>
                </button>
                <button @click="layout[editingContext.ci].settings.justifyContent = 'center'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'center' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="8" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="5" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="6.5" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Center</div>
                </button>
                <button @click="layout[editingContext.ci].settings.justifyContent = 'flex-end'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'flex-end' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="12" y="5" width="8" height="3" rx="0.5"/>
                        <rect x="6" y="10.5" width="14" height="3" rx="0.5"/>
                        <rect x="9" y="16" width="11" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Justify Right</div>
                </button>
                <button @click="layout[editingContext.ci].settings.justifyContent = 'space-between'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'space-between' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 16v-3h12v3l4-4-4-4v3H6V8l-4 4 4 4z" fill="currentColor"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Between</div>
                </button>
                <button @click="layout[editingContext.ci].settings.justifyContent = 'space-around'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'space-around' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="6" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="11" width="16" height="3" rx="0.5"/>
                        <rect x="4" y="16" width="16" height="3" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Around</div>
                </button>
                <button @click="layout[editingContext.ci].settings.justifyContent = 'space-evenly'" 
                        :class="layout[editingContext.ci].settings.justifyContent === 'space-evenly' ? 'bg-[#0091ea] text-white' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                        class="py-2 rounded transition-colors flex items-center justify-center relative group/btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="4" y="9.5" width="16" height="5" rx="0.5"/>
                    </svg>
                    <div class="lazy-tooltip-v2 opacity-0 group-hover/btn:opacity-100 z-[100] whitespace-nowrap">Space Evenly</div>
                </button>
            </div>
        </div>

        <!-- Content Wrap -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Content Wrap</label>
                <div class="flex gap-2 text-slate-300">
                    <i class="fa fa-question-circle text-[10px]"></i>
                    <i class="fa fa-desktop text-[10px]"></i>
                </div>
            </div>
            <div class="flex bg-slate-100 rounded overflow-hidden">
                <button @click="layout[editingContext.ci].settings.flexWrap = 'wrap'" 
                        :class="layout[editingContext.ci].settings.flexWrap === 'wrap' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">Wrap</button>
                <button @click="layout[editingContext.ci].settings.flexWrap = 'nowrap'" 
                        :class="layout[editingContext.ci].settings.flexWrap === 'nowrap' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                        class="flex-1 py-1.5 text-[10px] font-medium transition-colors">No Wrap</button>
            </div>
        </div>

        <!-- Column Spacing -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Column Spacing</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="text" v-model="layout[editingContext.ci].settings.columnGap" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>

        <!-- Container HTML Tag -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Container HTML Tag</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="layout[editingContext.ci].settings.htmlTag" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="div">Default</option>
                <option value="header">Header</option>
                <option value="footer">Footer</option>
                <option value="main">Main</option>
                <option value="article">Article</option>
                <option value="section">Section</option>
            </select>
        </div>

        <!-- Name Of Menu Anchor -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Name Of Menu Anchor</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="text" v-model="layout[editingContext.ci].settings.menuAnchor" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>

        <!-- Container Visibility -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Container Visibility</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <div class="flex gap-0.5 rounded overflow-hidden">
                <button @click="layout[editingContext.ci].settings.visibility.mobile = !layout[editingContext.ci].settings.visibility.mobile" 
                        :class="layout[editingContext.ci].settings.visibility.mobile ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-mobile-alt text-[11px]"></i>
                </button>
                <button @click="layout[editingContext.ci].settings.visibility.tablet = !layout[editingContext.ci].settings.visibility.tablet" 
                        :class="layout[editingContext.ci].settings.visibility.tablet ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-tablet-alt text-[11px]"></i>
                </button>
                <button @click="layout[editingContext.ci].settings.visibility.desktop = !layout[editingContext.ci].settings.visibility.desktop" 
                        :class="layout[editingContext.ci].settings.visibility.desktop ? 'bg-[#0091ea] text-white' : 'bg-slate-200 text-slate-400'"
                        class="flex-1 py-1.5 transition-colors flex items-center justify-center">
                    <i class="fa fa-desktop text-[11px]"></i>
                </button>
            </div>
        </div>

        <!-- Container Publishing Status -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">Container Publishing Status</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <select v-model="layout[editingContext.ci].settings.status" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <!-- CSS Class -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="text-[11px] font-bold text-[#444]">CSS Class</label>
                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
            </div>
            <input type="text" v-model="layout[editingContext.ci].settings.cssClass" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
        </div>
        </div>

        <!-- Design Tab -->
        <div v-show="activePanelTab === 'design'" class="space-y-6">
            <!-- Margin -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Margin</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-desktop text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.marginTop" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.marginBottom" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Padding -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Padding</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-desktop text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.paddingTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.paddingRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.paddingBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.paddingLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Container Link Color -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Container Link Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                        <i class="fa fa-undo text-[10px]"></i>
                        <i class="fa fa-circle text-[10px] text-white border border-slate-300 rounded-full"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input type="color" v-model="layout[editingContext.ci].settings.linkColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                    <div class="relative flex-1">
                        <input type="text" v-model="layout[editingContext.ci].settings.linkColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]" placeholder="#000000">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Container Border Size -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Container Border Size</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-cog text-[10px]"></i>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderSizeTop" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Right</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderSizeRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bottom</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderSizeBottom" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Left</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderSizeLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Container Border Color (Conditional) -->
            <div v-if="layout[editingContext.ci].settings.borderSizeTop > 0 || layout[editingContext.ci].settings.borderSizeRight > 0 || layout[editingContext.ci].settings.borderSizeBottom > 0 || layout[editingContext.ci].settings.borderSizeLeft > 0">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Container Border Color</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-question-circle text-[10px]"></i>
                        <i class="fa fa-database text-[10px]"></i>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <input type="color" v-model="layout[editingContext.ci].settings.borderColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                    <div class="relative flex-1">
                        <input type="text" v-model="layout[editingContext.ci].settings.borderColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Border Radius -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Border Radius</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <div class="grid grid-cols-4 gap-1">
                    <div>
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top/Left</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderRadiusTopLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Top/Right</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderRadiusTopRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bot/Right</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderRadiusBottomRight" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                    <div>
                        <label class="block text-[7px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Bot/Left</label>
                        <input type="number" v-model="layout[editingContext.ci].settings.borderRadiusBottomLeft" class="w-full border border-slate-200 rounded px-1.5 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    </div>
                </div>
            </div>

            <!-- Box Shadow -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Box Shadow</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <div class="flex w-[100px] bg-slate-100 rounded overflow-hidden">
                    <button @click="layout[editingContext.ci].settings.boxShadow = true" 
                            :class="layout[editingContext.ci].settings.boxShadow ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Yes</button>
                    <button @click="layout[editingContext.ci].settings.boxShadow = false" 
                            :class="!layout[editingContext.ci].settings.boxShadow ? 'bg-slate-200 text-slate-500' : 'text-slate-500 hover:bg-slate-200'"
                            class="flex-1 py-1.5 text-[10px] font-bold transition-colors">No</button>
                </div>
            </div>

            <template v-if="layout[editingContext.ci].settings.boxShadow">
                <!-- Box Shadow Position -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Position</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Vertical</label>
                            <input type="number" v-model="layout[editingContext.ci].settings.boxShadowPositionVertical" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Horizontal</label>
                            <input type="number" v-model="layout[editingContext.ci].settings.boxShadowPositionHorizontal" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        </div>
                    </div>
                </div>

                <!-- Box Shadow Blur Radius -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Blur Radius</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" v-model="layout[editingContext.ci].settings.boxShadowBlurRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model="layout[editingContext.ci].settings.boxShadowBlurRadius" min="0" max="100" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>

                <!-- Box Shadow Spread Radius -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Spread Radius</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="number" v-model="layout[editingContext.ci].settings.boxShadowSpreadRadius" class="w-16 border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                        <input type="range" v-model="layout[editingContext.ci].settings.boxShadowSpreadRadius" min="-50" max="100" class="flex-1 accent-[#0091ea]">
                    </div>
                </div>

                <!-- Box Shadow Color -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Color</label>
                        <div class="flex gap-2 text-slate-300">
                            <i class="fa fa-question-circle text-[10px]"></i>
                            <i class="fa fa-database text-[10px]"></i>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center">
                        <input type="color" v-model="layout[editingContext.ci].settings.boxShadowColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                        <div class="relative flex-1">
                            <input type="text" v-model="layout[editingContext.ci].settings.boxShadowColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Box Shadow Style -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-[11px] font-bold text-[#444]">Box Shadow Style</label>
                        <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                    </div>
                    <div class="flex w-[120px] bg-slate-100 rounded overflow-hidden">
                        <button @click="layout[editingContext.ci].settings.boxShadowStyle = 'outer'" 
                                :class="layout[editingContext.ci].settings.boxShadowStyle === 'outer' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Outer</button>
                        <button @click="layout[editingContext.ci].settings.boxShadowStyle = 'inner'" 
                                :class="layout[editingContext.ci].settings.boxShadowStyle === 'inner' ? 'bg-slate-200 text-slate-500' : 'text-slate-500 hover:bg-slate-200'"
                                class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Inner</button>
                    </div>
                </div>
            </template>

            <!-- Z Index -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Z Index</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <input type="number" v-model="layout[editingContext.ci].settings.zIndex" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
            </div>

            <!-- Overflow -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Overflow</label>
                    <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                </div>
                <select v-model="layout[editingContext.ci].settings.overflow" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                    <option value="default">Default</option>
                    <option value="hidden">Hidden</option>
                    <option value="auto">Auto</option>
                    <option value="scroll">Scroll</option>
                    <option value="visible">Visible</option>
                </select>
            </div>

        </div>

        <!-- Background Tab -->
        <div v-show="activePanelTab === 'background'" class="space-y-6">
            
            <!-- Background Options -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-[11px] font-bold text-[#444]">Background Options</label>
                    <div class="flex gap-2 text-slate-300">
                        <i class="fa fa-chevron-down text-[10px]"></i>
                        <i class="fa fa-question-circle text-[10px]"></i>
                    </div>
                </div>
                
                <!-- Sub Tabs for Background Type -->
                <div class="flex border border-slate-200 rounded overflow-hidden bg-slate-50 mb-4">
                    <button @click="layout[editingContext.ci].settings.bgType = 'color'" title="Background Color" :class="layout[editingContext.ci].settings.bgType === 'color' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-fill-drip"></i></button>
                    <button @click="layout[editingContext.ci].settings.bgType = 'gradient'" title="Background Gradient" :class="layout[editingContext.ci].settings.bgType === 'gradient' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-adjust"></i></button>
                    <button @click="layout[editingContext.ci].settings.bgType = 'image'" title="Background Image" :class="layout[editingContext.ci].settings.bgType === 'image' ? 'text-[#0091ea] bg-white border-b-2 border-[#0091ea]' : 'text-slate-400 hover:text-[#0091ea]'" class="flex-1 py-2 text-[12px]"><i class="fa fa-image"></i></button>
                </div>

                <!-- 1. Color Tab Content -->
                <div v-show="layout[editingContext.ci].settings.bgType === 'color'" class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Container Background Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-desktop text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="color" v-model="layout[editingContext.ci].settings.bgColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                            <div class="relative flex-1">
                                <input type="text" v-model="layout[editingContext.ci].settings.bgColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Gradient Tab Content -->
                <div v-show="layout[editingContext.ci].settings.bgType === 'gradient'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    <!-- Start Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="color" v-model="layout[editingContext.ci].settings.bgGradientStartColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                            <div class="relative flex-1">
                                <input type="text" v-model="layout[editingContext.ci].settings.bgGradientStartColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- End Color -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Color</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-cog text-[10px]"></i>
                                <i class="fa fa-undo text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="color" v-model="layout[editingContext.ci].settings.bgGradientEndColor" class="w-6 h-6 p-0 border-0 rounded cursor-pointer appearance-none bg-transparent">
                            <div class="relative flex-1">
                                <input type="text" v-model="layout[editingContext.ci].settings.bgGradientEndColor" class="w-full border border-slate-200 rounded px-2 py-1.5 pl-2 pr-8 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <i class="fa fa-globe absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Start Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Start Position</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="layout[editingContext.ci].settings.bgGradientStartPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="layout[editingContext.ci].settings.bgGradientStartPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- End Position -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient End Position</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="layout[editingContext.ci].settings.bgGradientEndPosition" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="100" v-model="layout[editingContext.ci].settings.bgGradientEndPosition" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>

                    <!-- Type -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Type</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex bg-slate-100 rounded overflow-hidden">
                            <button @click="layout[editingContext.ci].settings.bgGradientType = 'linear'" 
                                    :class="layout[editingContext.ci].settings.bgGradientType === 'linear' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Linear</button>
                            <button @click="layout[editingContext.ci].settings.bgGradientType = 'radial'" 
                                    :class="layout[editingContext.ci].settings.bgGradientType === 'radial' ? 'bg-[#0091ea] text-white' : 'text-slate-500 hover:bg-slate-200'"
                                    class="flex-1 py-1.5 text-[10px] font-bold transition-colors">Radial</button>
                        </div>
                    </div>

                    <!-- Angle -->
                    <div v-show="layout[editingContext.ci].settings.bgGradientType === 'linear'">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Gradient Angle</label>
                            <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                        </div>
                        <div class="flex gap-2 items-center">
                            <input type="number" v-model="layout[editingContext.ci].settings.bgGradientAngle" class="w-16 border border-slate-200 rounded px-2 py-1 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                            <input type="range" min="0" max="360" v-model="layout[editingContext.ci].settings.bgGradientAngle" class="flex-1 h-1 bg-[#0091ea] rounded appearance-none cursor-pointer">
                        </div>
                    </div>
                </div>

                <!-- 3. Image Tab Content -->
                <div v-show="layout[editingContext.ci].settings.bgType === 'image'" class="space-y-4 border border-slate-100 rounded-md p-2">
                    
                    <!-- Image Selection -->
                    <div class="border-b border-slate-100 pb-3">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[11px] font-bold text-[#444]">Background Image</label>
                            <div class="flex gap-2 text-slate-300">
                                <i class="fa fa-question-circle text-[10px]"></i>
                                <i class="fa fa-desktop text-[10px]"></i>
                                <i class="fa fa-database text-[10px]"></i>
                            </div>
                        </div>
                        <div v-if="layout[editingContext.ci].settings.bgImage" class="relative group">
                            <img :src="layout[editingContext.ci].settings.bgImage" class="w-full h-[120px] object-cover rounded border border-slate-200">
                            <div class="flex justify-center gap-2 mt-2">
                                <button @click="layout[editingContext.ci].settings.bgImage = ''" class="px-3 py-1.5 text-[11px] font-bold border border-slate-200 rounded text-[#444] hover:bg-slate-50 transition-colors">Remove</button>
                                <button @click="openMediaModal('bgImage')" class="px-3 py-1.5 text-[11px] font-bold bg-[#0091ea] text-white rounded hover:bg-[#007cc0] transition-colors">Edit</button>
                            </div>
                        </div>
                        <div v-else>
                            <button @click="openMediaModal('bgImage')" class="w-full h-[80px] border border-slate-200 bg-slate-50 hover:bg-slate-100 transition-colors rounded flex flex-col items-center justify-center gap-1">
                                <i class="fa fa-plus text-[#0091ea] text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <template v-if="layout[editingContext.ci].settings.bgImage">
                        <!-- Skip Lazy Loading -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Skip Lazy Loading</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <div class="flex bg-slate-100 rounded overflow-hidden w-[100px]">
                                <button @click="layout[editingContext.ci].settings.bgImageSkipLazy = true" 
                                        :class="layout[editingContext.ci].settings.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">Yes</button>
                                <button @click="layout[editingContext.ci].settings.bgImageSkipLazy = false" 
                                        :class="!layout[editingContext.ci].settings.bgImageSkipLazy ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">No</button>
                            </div>
                        </div>

                        <!-- Background Position -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Position</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="layout[editingContext.ci].settings.bgImagePosition" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="top left">Top Left</option>
                                <option value="top center">Top Center</option>
                                <option value="top right">Top Right</option>
                                <option value="center left">Center Left</option>
                                <option value="center center">Center Center</option>
                                <option value="center right">Center Right</option>
                                <option value="bottom left">Bottom Left</option>
                                <option value="bottom center">Bottom Center</option>
                                <option value="bottom right">Bottom Right</option>
                            </select>
                        </div>

                        <!-- Background Repeat -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Repeat</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="layout[editingContext.ci].settings.bgImageRepeat" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="no-repeat">No Repeat</option>
                                <option value="repeat">Repeat</option>
                                <option value="repeat-x">Repeat X</option>
                                <option value="repeat-y">Repeat Y</option>
                            </select>
                        </div>

                        <!-- Background Size -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Size</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="layout[editingContext.ci].settings.bgImageSize" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="auto">Default</option>
                                <option value="cover">Cover</option>
                                <option value="contain">Contain</option>
                            </select>
                        </div>

                        <!-- Fading Animation -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Fading Animation</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <div class="flex bg-slate-100 rounded overflow-hidden w-[100px]">
                                <button @click="layout[editingContext.ci].settings.bgImageFading = true" 
                                        :class="layout[editingContext.ci].settings.bgImageFading ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">Yes</button>
                                <button @click="layout[editingContext.ci].settings.bgImageFading = false" 
                                        :class="!layout[editingContext.ci].settings.bgImageFading ? 'bg-slate-800 text-white' : 'text-slate-500 hover:bg-slate-200'"
                                        class="flex-1 py-1 text-[10px] font-medium transition-colors">No</button>
                            </div>
                        </div>

                        <!-- Background Parallax -->
                        <div class="border-b border-slate-100 pb-3">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Parallax</label>
                                <i class="fa fa-question-circle text-[10px] text-slate-300"></i>
                            </div>
                            <select v-model="layout[editingContext.ci].settings.bgImageParallax" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="none">No Parallax (no effects)</option>
                                <option value="fixed">Fixed</option>
                            </select>
                        </div>

                        <!-- Background Blend Mode -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-[11px] font-bold text-[#444]">Background Blend Mode</label>
                                <div class="flex gap-2 text-slate-300">
                                    <i class="fa fa-question-circle text-[10px]"></i>
                                    <i class="fa fa-desktop text-[10px]"></i>
                                </div>
                            </div>
                            <select v-model="layout[editingContext.ci].settings.bgImageBlendMode" class="w-full border border-slate-200 rounded px-2 py-1.5 text-[11px] text-[#444] focus:outline-none focus:border-[#0091ea]">
                                <option value="normal">Disabled</option>
                                <option value="multiply">Multiply</option>
                                <option value="screen">Screen</option>
                                <option value="overlay">Overlay</option>
                                <option value="darken">Darken</option>
                                <option value="lighten">Lighten</option>
                            </select>
                        </div>
                    </template>

                </div>

            </div>
        </div>
    </div>
</div>
