<script>
    const { createApp, ref, reactive, computed, onMounted, watch } = Vue;

    createApp({
        setup() {
            const layout = ref([]);
            const isPreview = ref(false);
            const isSaving = ref(false);
            const activeTab = ref('navigator'); // Default to Navigator like in screenshot
            const device = ref('desktop');
            const activeCi = ref(null);
            const editingCi = ref(null);
            const activeColi = ref(null);
            const activeColCi = ref(null);
            const editingContext = ref({ type: null, ci: null, coli: null });
            const activePanelTab = ref('general');

            const isDragging = ref(false);
            const dragType = ref(null);
            const dragCi = ref(null);
            const startY = ref(0);
            const startX = ref(0);
            const startVal = ref(0);

            const showColumnModal = ref(false);
            const columnModalTarget = ref(null);
            
            const searchColumnQuery = ref('');
            const searchElementQuery = ref('');

            const columnLayouts = [
                // Row 1
                { id: '1', label: '1/1', config: '1/1' },
                { id: '2', label: '1/2 - 1/2', config: '1/2-1/2' },
                { id: '3', label: '1/3 - 1/3 - 1/3', config: '1/3-1/3-1/3' },
                { id: '4', label: '1/4 - 1/4 - 1/4 - 1/4', config: '1/4-1/4-1/4-1/4' },
                { id: '5', label: '2/3 - 1/3', config: '2/3-1/3' },
                { id: '6', label: '1/3 - 2/3', config: '1/3-2/3' },
                { id: '7', label: '1/4 - 3/4', config: '1/4-3/4' },
                // Row 2
                { id: '8', label: '3/4 - 1/4', config: '3/4-1/4' },
                { id: '9', label: '1/2 - 1/4 - 1/4', config: '1/2-1/4-1/4' },
                { id: '10', label: '1/4 - 1/4 - 1/2', config: '1/4-1/4-1/2' },
                { id: '11', label: '1/4 - 1/2 - 1/4', config: '1/4-1/2-1/4' },
                { id: '12', label: '1/5 - 4/5', config: '1/5-4/5' },
                { id: '13', label: '4/5 - 1/5', config: '4/5-1/5' },
                { id: '14', label: '3/5 - 2/5', config: '3/5-2/5' },
                // Row 3
                { id: '15', label: '2/5 - 3/5', config: '2/5-3/5' },
                { id: '16', label: '1/5 - 1/5 - 3/5', config: '1/5-1/5-3/5' },
                { id: '17', label: '1/5 - 3/5 - 1/5', config: '1/5-3/5-1/5' },
                { id: '18', label: '1/2 - 1/6 - 1/6 - 1/6', config: '1/2-1/6-1/6-1/6' },
                { id: '19', label: '1/6 - 1/6 - 1/6 - 1/2', config: '1/6-1/6-1/6-1/2' },
                { id: '20', label: '1/6 - 2/3 - 1/6', config: '1/6-2/3-1/6' },
                { id: '21', label: '1/5 - 1/5 - 1/5 - 1/5 - 1/5', config: '1/5-1/5-1/5-1/5-1/5' },
                // Row 4
                { id: '22', label: '1/6 - 1/6 - 1/6 - 1/6 - 1/6 - 1/6', config: '1/6-1/6-1/6-1/6-1/6-1/6' },
                { id: '23', label: '5/6', config: '5/6' },
                { id: '24', label: '4/5', config: '4/5' },
                { id: '25', label: '3/4', config: '3/4' },
                { id: '26', label: '2/3', config: '2/3' },
                { id: '27', label: '3/5', config: '3/5' },
                { id: '28', label: '1/2', config: '1/2' },
                // Row 5
                { id: '29', label: '2/5', config: '2/5' },
                { id: '30', label: '1/3', config: '1/3' },
                { id: '31', label: '1/4', config: '1/4' },
                { id: '32', label: '1/5', config: '1/5' },
                { id: '33', label: '1/6', config: '1/6' },
            ];

            const availableElements = [
                { type: 'heading', name: 'Heading', icon: 'fa fa-heading' },
                { type: 'text', name: 'Text Block', icon: 'fa fa-paragraph' },
                { type: 'image', name: 'Image', icon: 'fa fa-image' },
                { type: 'button', name: 'Button', icon: 'fa fa-rectangle-ad' },
                { type: 'video', name: 'Video', icon: 'fa fa-play-circle' },
                { type: 'spacer', name: 'Spacer', icon: 'fa fa-arrows-alt-v' },
            ];

            const filteredColumnLayouts = computed(() => {
                if (!searchColumnQuery.value) return columnLayouts;
                const query = searchColumnQuery.value.toLowerCase();
                return columnLayouts.filter(layout => layout.label.toLowerCase().includes(query) || layout.config.toLowerCase().includes(query));
            });

            const filteredNestedColumnLayouts = computed(() => {
                if (!searchElementQuery.value) return columnLayouts;
                const query = searchElementQuery.value.toLowerCase();
                return columnLayouts.filter(layout => layout.label.toLowerCase().includes(query) || layout.config.toLowerCase().includes(query));
            });

            const filteredAvailableElements = computed(() => {
                if (!searchElementQuery.value) return availableElements;
                const query = searchElementQuery.value.toLowerCase();
                return availableElements.filter(el => 
                    (el.name && el.name.toLowerCase().includes(query)) || 
                    el.type.toLowerCase().includes(query)
                );
            });

            // Initialize layout
            onMounted(() => {
                const rawContent = @json($post->content);
                try {
                    if (rawContent) {
                        const parsed = typeof rawContent === 'string' ? JSON.parse(rawContent) : rawContent;
                        
                        // Recursive migration for basis and visual gap (padding)
                        const migrateBasis = (columns) => {
                            if (!columns || !columns.length) return;
                            const total = columns.length;
                            columns.forEach(col => {
                                // Fix shrinking issue for old columns
                                if (!col.basis) col.basis = 100 / total;
                                
                                // Fix missing visual gap issue for old columns
                                if (col.settings.paddingLeft === undefined) col.settings.paddingLeft = 10;
                                if (col.settings.paddingRight === undefined) col.settings.paddingRight = 10;

                                if (col.elements) {
                                    col.elements.forEach(el => {
                                        if (el.type === 'row' && el.columns) migrateBasis(el.columns);
                                    });
                                }
                            });
                        };
                        
                        if (Array.isArray(parsed)) {
                            parsed.forEach(row => {
                                if (!row.settings) row.settings = {};
                                if (!row.settings.visibility) row.settings.visibility = { mobile: true, tablet: true, desktop: true };
                                if (row.settings.contentWidth === undefined) row.settings.contentWidth = 'site';
                                if (row.settings.height === undefined) row.settings.height = 'auto';
                                if (row.settings.customHeight === undefined) row.settings.customHeight = '';
                                if (row.settings.alignItems === undefined) row.settings.alignItems = 'stretch';
                                if (row.settings.alignContent === undefined) row.settings.alignContent = 'flex-start';
                                if (row.settings.justifyContent === undefined) row.settings.justifyContent = 'flex-start';
                                if (row.settings.flexWrap === undefined) row.settings.flexWrap = 'wrap';
                                if (row.settings.columnGap === undefined) row.settings.columnGap = '';
                                if (row.settings.htmlTag === undefined) row.settings.htmlTag = 'div';
                                if (row.settings.status === undefined) row.settings.status = 'published';

                                if (row.columns) migrateBasis(row.columns);
                            });
                        }
                        
                        layout.value = parsed;
                    }
                } catch (e) {
                    console.error('Failed to parse layout', e);
                    layout.value = [];
                }
            });

            const uid = () => Math.random().toString(36).substr(2, 9);

            const columnModalType = ref('new'); // 'new' or 'edit'
            
            const openColumnModal = (index = null, type = 'new') => {
                columnModalTarget.value = index;
                columnModalType.value = type;
                showColumnModal.value = true;
            };

            const selectLayout = (layoutData) => {
                const columns = layoutData.config.split('-').map(part => {
                    const [num, den] = part.split('/');
                    return {
                        id: uid(),
                        basis: Number((num / den) * 100),
                        settings: { 
                            paddingTop: 10, paddingBottom: 10, 
                            paddingLeft: 10, paddingRight: 10,
                            marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0 
                        },
                        elements: []
                    };
                });

                if (columnModalType.value === 'edit') {
                    // Append mode
                    const ci = columnModalTarget.value;
                    const container = layout.value[ci];
                    
                    // Add the new columns with their correctly calculated basis
                    container.columns.push(...columns);
                    activeCi.value = ci;
                } else {

                    // Add new container
                    const newContainer = {
                        id: uid(),
                        settings: {
                            marginTop: '', marginBottom: '',
                            paddingTop: 0, paddingBottom: 0,
                            paddingLeft: 0, paddingRight: 0,
                            bgColor: '#ffffff', bgType: 'color',
                            contentWidth: 'site', height: 'auto', customHeight: '',
                            alignItems: 'stretch', alignContent: 'flex-start', justifyContent: 'flex-start',
                            flexWrap: 'wrap', columnGap: '', htmlTag: 'div',
                            menuAnchor: '', visibility: { mobile: true, tablet: true, desktop: true },
                            status: 'published', cssClass: '',
                            linkColor: '',
                            borderSizeTop: '', borderSizeRight: '', borderSizeBottom: '', borderSizeLeft: '', borderColor: '#000000',
                            borderRadiusTopLeft: '', borderRadiusTopRight: '', borderRadiusBottomRight: '', borderRadiusBottomLeft: '',
                            boxShadow: false, boxShadowPositionVertical: 0, boxShadowPositionHorizontal: 0,
                            boxShadowBlurRadius: 0, boxShadowSpreadRadius: 0, boxShadowColor: '#000000', boxShadowStyle: 'outer',
                            zIndex: '', overflow: 'default'
                        },
                        columns: columns
                    };

                    if (columnModalTarget.value !== null) {
                        layout.value.splice(columnModalTarget.value, 0, newContainer);
                        activeCi.value = columnModalTarget.value;
                    } else {
                        layout.value.push(newContainer);
                        activeCi.value = layout.value.length - 1;
                    }
                }

                showColumnModal.value = false;
            };

            const addContainer = (index = null) => {
                openColumnModal(index);
            };

            const addColumn = (ci) => {
                const col = {
                    id: uid(),
                    basis: 100,
                    settings: { 
                        paddingTop: 10, paddingBottom: 10, paddingLeft: 10, paddingRight: 10,
                        marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0 
                    },
                    elements: []
                };
                layout.value[ci].columns.push(col);
            };

            const addNestedColumn = (ci, coli, eli) => {
                const row = layout.value[ci].columns[coli].elements[eli];
                row.columns.push({
                    id: uid(),
                    basis: 100,
                    settings: { 
                        paddingTop: 10, paddingBottom: 10, paddingLeft: 10, paddingRight: 10,
                        marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0 
                    },
                    elements: []
                });
            };


            const duplicateContainer = (ci) => {
                const clone = JSON.parse(JSON.stringify(layout.value[ci]));
                clone.id = uid();
                layout.value.splice(ci + 1, 0, clone);
            };

            const duplicateColumn = (ci, coli) => {
                const clone = JSON.parse(JSON.stringify(layout.value[ci].columns[coli]));
                clone.id = uid();
                layout.value[ci].columns.splice(coli + 1, 0, clone);
            };

            const duplicateNestedColumn = (ci, coli, eli, ncoli) => {
                const row = layout.value[ci].columns[coli].elements[eli];
                const clone = JSON.parse(JSON.stringify(row.columns[ncoli]));
                clone.id = uid();
                row.columns.splice(ncoli + 1, 0, clone);
            };

            const duplicateNestedRow = (ci, coli, eli) => {
                const column = layout.value[ci].columns[coli];
                const clone = JSON.parse(JSON.stringify(column.elements[eli]));
                clone.id = uid();
                column.elements.splice(eli + 1, 0, clone);
            };

            // Full HTML5 Drag and Drop Logic for Reordering
            const dragSource = ref(null);
            const dragTarget = ref(null); // Used for visual highlighting
            const dragPosition = ref('top'); // top, bottom, left, right

            const onDragStart = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                dragSource.value = { type, ci, coli, eli, ncoli, neli };
                if (e.dataTransfer) {
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', type);
                    // Add slight opacity to the dragged element
                    setTimeout(() => {
                        if (e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el')) {
                            e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el').style.opacity = '0.5';
                        }
                    }, 0);
                }
            };

            const onDragEnd = (e) => {
                if (e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el')) {
                    e.target.closest('.group\\/cont, .group\\/col, .group\\/ncol, .group\\/nrow, .group\\/el').style.opacity = '1';
                }
                dragSource.value = null;
                dragTarget.value = null;
            };

            const onDragOver = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                if (!dragSource.value) return;
                
                // Allow dropping only on same type
                if (dragSource.value.type !== type) return;

                // For elements, strictly enforce nesting level match to prevent jittering
                if (type === 'element') {
                    const srcIsNested = dragSource.value.ncoli !== null;
                    const targetIsNested = ncoli !== null;
                    if (srcIsNested !== targetIsNested) return;
                }

                // Calculate whether mouse is in the first half or second half
                const rect = e.currentTarget.getBoundingClientRect();
                let pos = 'top';
                if (type === 'column' || type === 'nested-column') {
                    pos = e.clientX > rect.left + rect.width / 2 ? 'right' : 'left';
                } else {
                    pos = e.clientY > rect.top + rect.height / 2 ? 'bottom' : 'top';
                }

                if (dragPosition.value !== pos) dragPosition.value = pos;

                // Since types match, this is our intended drop target!
                e.preventDefault(); 
                e.stopPropagation();

                const targetId = `${type}-${ci}-${coli}-${eli}-${ncoli}-${neli}`;
                if (dragTarget.value !== targetId) {
                    dragTarget.value = targetId;
                }
            };

            const onDragLeave = (e) => {
                // dragTarget.value = null; 
            };

            const moveItem = (arr, srcIdx, targetIdx, position) => {
                if (srcIdx === targetIdx) return;
                const targetItem = arr[targetIdx];
                const item = arr.splice(srcIdx, 1)[0];
                
                let newTargetIdx = arr.indexOf(targetItem);
                if (newTargetIdx === -1) {
                    arr.splice(srcIdx, 0, item); // Safety fallback
                    return;
                }
                
                let insertIdx = newTargetIdx;
                if (position === 'bottom' || position === 'right') insertIdx += 1;
                
                arr.splice(insertIdx, 0, item);
            };

            const onDrop = (e, type, ci, coli = null, eli = null, ncoli = null, neli = null) => {
                if (!dragSource.value) return;
                const src = dragSource.value;
                
                // Must be same type
                if (src.type !== type) return;

                if (type === 'element') {
                    const srcIsNested = src.ncoli !== null;
                    const targetIsNested = ncoli !== null;
                    if (srcIsNested !== targetIsNested) return;
                }
                
                // We matched, so stop it from bubbling up
                e.preventDefault();
                e.stopPropagation();
                
                const pos = dragPosition.value;
                dragTarget.value = null;

                if (src.ci === ci && src.coli === coli && src.eli === eli && src.ncoli === ncoli && src.neli === neli) return;

                if (type === 'container') {
                    moveItem(layout.value, src.ci, ci, pos);
                } 
                else if (type === 'column') {
                    if (src.ci !== ci) return;
                    moveItem(layout.value[ci].columns, src.coli, coli, pos);
                }
                else if (type === 'nested-column') {
                    if (src.ci !== ci || src.coli !== coli || src.eli !== eli) return;
                    moveItem(layout.value[ci].columns[coli].elements[eli].columns, src.ncoli, ncoli, pos);
                }
                else if (type === 'element') {
                    if (src.ci !== ci || src.coli !== coli) return;
                    
                    let targetList;
                    if (ncoli !== null) {
                        if (src.eli !== eli || src.ncoli !== ncoli) return;
                        targetList = layout.value[ci].columns[coli].elements[eli].columns[ncoli].elements;
                    } else {
                        targetList = layout.value[ci].columns[coli].elements;
                    }

                    const srcIdx = (src.neli !== null) ? src.neli : src.eli;
                    const targetIdx = (neli !== null) ? neli : eli;
                    
                    moveItem(targetList, srcIdx, targetIdx, pos);
                }
            };

            // Existing visual resizing handles drag logic...
            const dragColi = ref(null);
            const dragEli = ref(null);
            const dragNcoli = ref(null);
            const isColumnDrag = ref(false);
            const isNestedDrag = ref(false);

            const startDrag = (e, type, ci, coli = null, eli = null, ncoli = null) => {
                isDragging.value = true;
                dragType.value = type;
                dragCi.value = ci;
                dragColi.value = coli;
                dragEli.value = eli;
                dragNcoli.value = ncoli;
                
                isColumnDrag.value = coli !== null && eli === null;
                isNestedDrag.value = ncoli !== null;
                
                startY.value = e.clientY;
                startX.value = e.clientX;
                
                let target;
                if (isNestedDrag.value) {
                    target = layout.value[ci].columns[coli].elements[eli].columns[ncoli];
                } else if (isColumnDrag.value) {
                    target = layout.value[ci].columns[coli];
                } else {
                    target = layout.value[ci];
                }

                if (!target.settings[type]) target.settings[type] = 0;
                startVal.value = target.settings[type] || 0;
                
                window.addEventListener('mousemove', handleDrag);
                window.addEventListener('mouseup', stopDrag);
                
                if (type.toLowerCase().includes('left') || type.toLowerCase().includes('right')) {
                    document.body.style.cursor = 'ew-resize';
                } else {
                    document.body.style.cursor = 'ns-resize';
                }
                
                document.body.classList.add('select-none');
            };
            
            const handleDrag = (e) => {
                if (!isDragging.value) return;
                const diffY = e.clientY - startY.value;
                const diffX = e.clientX - startX.value;
                let newVal = 0;
                
                const factor = (dragType.value === 'marginRight' || dragType.value === 'paddingRight' || dragType.value === 'marginBottom') ? -1 : 1;
                // Simple logic for all directions
                if (dragType.value.toLowerCase().includes('top') || dragType.value.toLowerCase().includes('bottom')) {
                    newVal = startVal.value + diffY;
                } else {
                    newVal = startVal.value + diffX;
                }

                // Handle specific inverse directions
                if (dragType.value === 'marginRight' || dragType.value === 'paddingRight') newVal = startVal.value - diffX;
                if (dragType.value === 'marginBottom' || dragType.value === 'paddingBottom') newVal = startVal.value + diffY;
                
                let target;
                if (isNestedDrag.value) {
                    target = layout.value[dragCi.value].columns[dragColi.value].elements[dragEli.value].columns[dragNcoli.value];
                } else if (isColumnDrag.value) {
                    target = layout.value[dragCi.value].columns[dragColi.value];
                } else {
                    target = layout.value[dragCi.value];
                }
                
                target.settings[dragType.value] = Math.max(0, newVal);
            };

            const stopDrag = () => {
                isDragging.value = false;
                dragType.value = null;
                dragCi.value = null;
                dragEli.value = null;
                dragNcoli.value = null;
                window.removeEventListener('mousemove', handleDrag);
                window.removeEventListener('mouseup', stopDrag);
                document.body.style.cursor = '';
                document.body.classList.remove('select-none');
            };

            const saveLayout = async () => {
                isSaving.value = true;
                try {
                    const response = await fetch("{{ route('admin.lazy-builder.save', $post->id) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ layout: layout.value })
                    });
                    const data = await response.json();
                    if (data.success) {
                        alert('Layout saved successfully!');
                    }
                } catch (e) {
                    console.error('Save failed', e);
                    alert('Save failed! Check console.');
                } finally {
                    isSaving.value = false;
                }
            };

            // Dynamic Styles
            const canvasStyle = computed(() => {
                if (device.value === 'mobile') return { width: '375px' };
                if (device.value === 'tablet') return { width: '768px' };
                return { width: '100%' };
            });

            const containerStyle = (container, ci) => {
                const s = container.settings;
                let mTop = Number(s.marginTop) || 0;
                if (ci === 0) mTop += 60;
                
                let boxShadowStr = 'none';
                if (s.boxShadow) {
                    const inset = s.boxShadowStyle === 'inner' ? 'inset ' : '';
                    boxShadowStr = `${inset}${s.boxShadowPositionHorizontal || 0}px ${s.boxShadowPositionVertical || 0}px ${s.boxShadowBlurRadius || 0}px ${s.boxShadowSpreadRadius || 0}px ${s.boxShadowColor || '#000000'}`;
                }

                return {
                    paddingTop: (s.paddingTop !== undefined && s.paddingTop !== '' ? s.paddingTop : 0) + 'px',
                    paddingBottom: (s.paddingBottom !== undefined && s.paddingBottom !== '' ? s.paddingBottom : 0) + 'px',
                    paddingLeft: (s.paddingLeft || 0) + 'px',
                    paddingRight: (s.paddingRight || 0) + 'px',
                    marginTop: mTop + 'px',
                    marginBottom: (s.marginBottom || 0) + 'px',
                    borderTopWidth: (s.borderSizeTop || 0) + 'px',
                    borderRightWidth: (s.borderSizeRight || 0) + 'px',
                    borderBottomWidth: (s.borderSizeBottom || 0) + 'px',
                    borderLeftWidth: (s.borderSizeLeft || 0) + 'px',
                    borderStyle: 'solid',
                    borderColor: s.borderColor || '#000000',
                    borderTopLeftRadius: (s.borderRadiusTopLeft || 0) + 'px',
                    borderTopRightRadius: (s.borderRadiusTopRight || 0) + 'px',
                    borderBottomRightRadius: (s.borderRadiusBottomRight || 0) + 'px',
                    borderBottomLeftRadius: (s.borderRadiusBottomLeft || 0) + 'px',
                    boxShadow: boxShadowStr,
                    zIndex: s.zIndex || 'auto',
                    overflow: s.overflow && s.overflow !== 'default' ? s.overflow : 'visible',
                    backgroundColor: 'transparent',
                    minHeight: (100 + Number(s.paddingTop || 0) + Number(s.paddingBottom || 0)) + 'px',
                    height: s.height === 'full' ? '100vh' : (s.height === 'custom' ? (s.customHeight || 'auto') : 'auto')
                };
            };

            const containerInnerStyle = (container) => {
                const s = container.settings;
                return {
                    maxWidth: s.contentWidth === '100%' ? '100%' : '1220px',
                    height: '100%',
                    alignItems: s.alignItems || 'stretch',
                    alignContent: s.alignContent || 'flex-start',
                    justifyContent: s.justifyContent || 'flex-start',
                    flexWrap: s.flexWrap || 'wrap',
                    columnGap: s.columnGap || '2%'
                };
            };

            const columnOuterStyle = (column, totalColumns) => {
                const width = column.basis || (100 / totalColumns);
                // Adjust width for the 2% gap requested by the user
                const flexBasis = width >= 100 ? '100%' : `calc(${width}% - 2%)`;
                return {
                    flex: `0 0 ${flexBasis}`,
                    maxWidth: `${flexBasis}`,
                    minWidth: '0',
                };
            };

            const columnInnerStyle = (column) => {
                const s = column.settings;
                return {
                    paddingTop: (s.paddingTop || 0) + 'px',
                    paddingBottom: (s.paddingBottom || 0) + 'px',
                    paddingLeft: (s.paddingLeft || 0) + 'px',
                    paddingRight: (s.paddingRight || 0) + 'px',
                    marginTop: (s.marginTop || 0) + 'px',
                    marginBottom: (s.marginBottom || 0) + 'px',
                    marginLeft: (s.marginLeft || 0) + 'px',
                    marginRight: (s.marginRight || 0) + 'px',
                    minHeight: (100 + (s.paddingTop || 0) + (s.paddingBottom || 0)) + 'px',
                };
            };

            const showElementModal = ref(false);
            const elementModalTab = ref('design'); // design, library, nested, studio
            const elementModalRestricted = ref(false);
            const currentTargetCi = ref(null);
            const currentTargetColi = ref(null);
            const currentTargetEli = ref(null);
            const currentTargetNcoli = ref(null);

            const openElementModal = (ci, coli = null, defaultTab = 'design', restricted = false, eli = null, ncoli = null) => {
                currentTargetCi.value = ci;
                currentTargetColi.value = coli;
                currentTargetEli.value = eli;
                currentTargetNcoli.value = ncoli;
                elementModalTab.value = defaultTab;
                elementModalRestricted.value = restricted;
                showElementModal.value = true;
            };

            const selectNestedLayout = (layoutConfig) => {
                if (currentTargetCi.value === null) return;
                
                const layoutData = {
                    id: Date.now(),
                    type: 'row',
                    settings: { 
                        paddingTop: 0, paddingBottom: 0, paddingLeft: 0, paddingRight: 0,
                        marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0
                    },
                    columns: layoutConfig.split('-').map((part, idx) => {
                        const [num, den] = part.split('/');
                        return {
                            id: Date.now() + idx,
                            basis: Number((num / den) * 100),
                            settings: {
                                paddingTop: 10, paddingBottom: 10, paddingLeft: 10, paddingRight: 10,
                                marginTop: 0, marginBottom: 0, marginLeft: 0, marginRight: 0
                            },
                            elements: []
                        };
                    })
                };

                if (currentTargetColi.value === null) {
                    layout.value[currentTargetCi.value].columns.push(...layoutData.columns);
                } else if (currentTargetEli.value === null) {
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.push(layoutData);
                } else {
                    // Update existing nested row's columns
                    const nestedRow = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value];
                    nestedRow.columns.push(...layoutData.columns);
                }
                
                showElementModal.value = false;
                currentTargetCi.value = null;
                currentTargetColi.value = null;
                currentTargetEli.value = null;
                currentTargetNcoli.value = null;
            };

            const addElement = (type) => {
                if (currentTargetCi.value === null || currentTargetColi.value === null) return;
                
                const newEl = {
                    id: Date.now(),
                    type: type,
                    settings: type === 'heading' ? { title: 'New Heading', textAlign: 'left' } : { content: '<p>New text here...</p>' }
                };

                if (currentTargetEli.value !== null && currentTargetNcoli.value !== null) {
                    // Nested insertion
                    const nestedCol = layout.value[currentTargetCi.value].columns[currentTargetColi.value].elements[currentTargetEli.value].columns[currentTargetNcoli.value];
                    nestedCol.elements.push(newEl);
                } else {
                    // Regular insertion
                    const column = layout.value[currentTargetCi.value].columns[currentTargetColi.value];
                    column.elements.push(newEl);
                }
                showElementModal.value = false;
            };

            const formatBasisToFraction = (basis) => {
                if (!basis) return '1/1';
                const b = Math.round(basis);
                if (b === 100) return '1/1';
                if (b === 83) return '5/6';
                if (b === 80) return '4/5';
                if (b === 75) return '3/4';
                if (b === 67) return '2/3';
                if (b === 60) return '3/5';
                if (b === 50) return '1/2';
                if (b === 40) return '2/5';
                if (b === 33) return '1/3';
                if (b === 25) return '1/4';
                if (b === 20) return '1/5';
                if (b === 17) return '1/6';
                return `${Math.round(basis / 8.33)}/12`;
            };

            return {
                layout, isPreview, isSaving, activeTab, activePanelTab, device, availableElements,
                activeCi, editingCi, activeColi, activeColCi, editingContext,
                showColumnModal, columnModalType, columnLayouts, openColumnModal, selectLayout,
                showElementModal, elementModalTab, elementModalRestricted, openElementModal, selectNestedLayout,
                addContainer, addColumn, addNestedColumn, addElement, duplicateContainer, duplicateColumn, duplicateNestedColumn, duplicateNestedRow, saveLayout,
                isDragging, dragType, dragCi, dragColi, dragEli, dragNcoli, startDrag,
                onDragStart, onDragEnd, onDragOver, onDrop, dragTarget, dragPosition,
                canvasStyle, containerStyle, containerInnerStyle, columnOuterStyle, columnInnerStyle, formatBasisToFraction,
                searchColumnQuery, searchElementQuery, filteredColumnLayouts, filteredNestedColumnLayouts, filteredAvailableElements
            };
        }
    }).mount('#lazy-builder-app');
</script>
