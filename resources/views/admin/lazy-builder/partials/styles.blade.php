<style>
    :root {
        --builder-bg: #ffffff;
        --sidebar-width: 300px;
        --topbar-height: 50px;
        --primary: #0091ea;
        --dark-bg: #1b1b1b;
        --border-color: #e1e1e1;
    }

    /* Basic Reset to prevent complete breakage without Tailwind */
    * { box-sizing: border-box; }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        background-color: var(--builder-bg);
        overflow: hidden;
        margin: 0;
        padding: 0;
        display: block;
        width: 100%;
        height: 100vh;
    }

    h1, h2, h3, .font-premium {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    [v-cloak] { display: none !important; }

    /* Builder Layout (Standard CSS as fallback) */
    .builder-wrapper {
        display: grid;
        grid-template-areas: 
            "topbar topbar"
            "sidebar canvas";
        grid-template-columns: var(--sidebar-width) 1fr;
        grid-template-rows: var(--topbar-height) 1fr;
        height: 100vh;
        width: 100%;
    }

    /* PREVIEW MODE - Grid column fix */
    .builder-wrapper.is-preview {
        grid-template-columns: 0 1fr !important;
    }

    .is-preview .container-row,
    .is-preview .column-box,
    .is-preview .column-inner,
    .is-preview .column-outer,
    .is-preview .nested-column,
    .is-preview .nested-row-wrapper,
    .is-preview .group\/ncol,
    .is-preview .column-outer {
        border-color: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .is-preview .container-handles,
    .is-preview .container-right-panel,
    .is-preview .column-left-panel,
    .is-preview .add-element-placeholder,
    .is-preview .column-add-btn,
    .is-preview .element-edit-panel,
    .is-preview .empty-column-placeholder,
    .is-preview .nested-column-toolbar,
    .is-preview .handle-orange,
    .is-preview .handle-blue,
    .is-preview .handle-purple {
        display: none !important;
    }
    .is-preview .canvas-container {
        padding: 0 !important;
    }

    .builder-topbar { 
        grid-area: topbar; 
        background: var(--dark-bg); 
        color: white;
        z-index: 50; 
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 15px;
        height: var(--topbar-height);
        width: 100%;
    }
    
    .builder-sidebar { 
        grid-area: sidebar; 
        background: #fff; 
        border-right: 1px solid var(--border-color); 
        z-index: 40; 
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }
    
    .builder-canvas-area {
        grid-area: canvas;
        overflow-y: auto;
        background-color: var(--builder-bg);
        display: flex;
        flex-direction: column;
        padding: 0;
        overflow-x: hidden;
    }

    /* Canvas Styles */
    .canvas-container {
        width: 100%;
        margin: 0 !important;
        background: #fff;
        min-height: calc(100vh - var(--topbar-height));
        box-shadow: none;
        border: none;
        transition: all 0.3s ease;
        position: relative;
        padding: 0 !important;
    }
    .container-row:first-child {
        margin-top: 60px;
    }


    /* Fix huge icons if Tailwind fails */
    svg {
        max-width: 100%;
        height: auto;
    }
    .topbar-icon svg { width: 20px; height: 20px; }
    .builder-sidebar svg { width: 24px; height: 24px; }
    i.fa { font-size: 14px; }

    /* Topbar Icons */
    .topbar-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: color 0.2s;
        cursor: pointer;
    }
    .topbar-icon:hover { color: white; }
    .topbar-icon.active { color: white; background: rgba(255,255,255,0.1); }

    .container-row {
        position: relative;
        transition: all 0.2s;
        border: 2px solid #bcdff1; /* persistent light blue border */
        margin-top: -2px;
        display: block;
        width: 100%;
        z-index: 10;
    }
    .container-row:hover, .container-active {
        border-color: var(--primary) !important;
        z-index: 10;
    }

    /* Column Styles */
    .column-box {
        position: relative;
        transition: border 0.2s;
        border: 2px solid transparent;
        margin-left: -2px;
        z-index: 5;
        display: flex;
        flex-direction: column;
        flex-shrink: 0; /* Bootstrap-like: don't shrink below content */
    }
    .column-box:hover {
        border: 2px solid #bcdff1 !important;
        z-index: 6;
    }
    .column-active {
        border: 2px solid var(--primary) !important;
        z-index: 7;
    }
    .preview-mode .column-box {
        border-color: transparent !important;
    }

    /* Padding/Margin Handles */
    .container-handles > div {
        position: absolute;
        z-index: 100;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .container-row:hover .container-handles > div,
    .container-active .container-handles > div {
        opacity: 1;
    }

    .handle-blue, .handle-purple, .handle-left, .handle-right {
        background: var(--primary);
        color: white;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 8px;
        border-radius: 2px;
    }
    .handle-blue, .handle-purple, .handle-blue-h, .handle-purple-h {
        width: 18px;
        height: 18px;
        background: #0091ea;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        pointer-events: auto;
        z-index: 120;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .handle-blue, .handle-purple { cursor: ns-resize !important; }
    .handle-blue-h, .handle-purple-h { cursor: ew-resize !important; }
    .handle-purple, .handle-purple-h { background: #9c27b0; }

    .handle-top { top: -10px; left: 50%; transform: translateX(-50%); position: absolute; }
    .handle-bottom { bottom: -10px; left: 50%; transform: translateX(-50%); position: absolute; }
    .handle-left { left: -10px; top: 50%; transform: translateY(-50%); position: absolute; }
    .handle-right { right: -10px; top: 50%; transform: translateY(-50%); position: absolute; }

    /* Right/Left Panels Toolbar */
    .container-right-panel {
        position: absolute;
        right: 0;
        top: 0;
        z-index: 110;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .column-left-panel {
        position: absolute;
        left: 0;
        top: 0;
        z-index: 110;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .container-row:hover .container-right-panel,
    .container-active .container-right-panel,
    .column-box:hover .column-left-panel,
    .column-active .column-left-panel {
        opacity: 1;
    }
    .panel-inner {
        background: var(--primary);
        border-radius: 4px;
        display: flex;
        padding: 2px;
        gap: 1px;
        align-items: center;
    }
    .panel-btn {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e1f5fe;
        cursor: pointer;
        border-radius: 2px;
        transition: all 0.2s;
        position: relative;
    }
    .column-label {
        color: white;
        font-size: 10px;
        font-weight: 800;
        padding: 0 6px;
        letter-spacing: 0.5px;
    }
    .panel-btn:hover {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    .panel-btn i { font-size: 14px; }

    /* Tooltips */
    .lazy-tooltip {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
        background: #1b1b1b;
        color: white;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 200;
    }
    .lazy-tooltip::after {
        content: '';
        position: absolute;
        bottom: 100%;
        right: 12px;
        border: 5px solid transparent;
        border-bottom-color: #1b1b1b;
    }
    .panel-btn:hover .lazy-tooltip {
        opacity: 1;
    }

    /* Navigator Item */
    .nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        cursor: pointer;
        border-bottom: 1px solid #f8fafc;
    }
    .nav-item.active {
        background: #eff6ff;
        border-left: 4px solid var(--primary);
    }

    /* Save Button */
    .btn-save {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 24px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-save:hover {
        background-color: #45a049;
    }
    .btn-save:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .dragging-no-transition, .dragging-no-transition * {
        transition: none !important;
    }
    .handle-orange, .handle-orange-h {
        width: 18px;
        height: 18px;
        background: #ff9800;
        border-radius: 3px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        pointer-events: auto;
        z-index: 120;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .handle-orange { cursor: ns-resize !important; }
    .handle-orange-h { cursor: ew-resize !important; }

    .panel-btn-orange {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        cursor: pointer;
        border-radius: 2px;
        transition: all 0.2s;
        position: relative;
    }
    .panel-btn-orange:hover {
        background: rgba(255,255,255,0.2);
    }
    .panel-btn-orange i { font-size: 14px; }

    .lazy-tooltip-v2 {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: 10px;
        background: #1b1b1b;
        color: white;
        text-transform: uppercase;
        font-size: 10px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s;
        z-index: 500;
    }
    .lazy-tooltip-v2::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #1b1b1b;
    }
    .panel-btn-orange:hover .lazy-tooltip-v2,
    .handle-orange:hover .lazy-tooltip-v2 {
        opacity: 1 !important;
        visibility: visible !important;
    }
    .nested-column-active {
        @apply ring-2 ring-[#ff9800] ring-offset-2;
    }

    /* WordPress Style Components for Media Modal */
    .wp-btn-primary {
        background: #2271b1;
        color: #fff;
        border: 1px solid #2271b1;
        border-radius: 3px;
        padding: 0 10px;
        min-height: 30px;
        font-size: 13px;
        line-height: 2.15384615;
        cursor: pointer;
        transition: all 0.1s;
        display: inline-flex;
        align-items: center;
        box-sizing: border-box;
    }
    .wp-btn-primary:hover {
        background: #135e96;
        border-color: #135e96;
    }
    .wp-btn-primary:disabled {
        background: #a7aaad !important;
        border-color: #a7aaad !important;
        color: #dcdcde !important;
        cursor: default !important;
        opacity: 0.7;
    }

    .wp-btn-secondary {
        color: #2271b1;
        border-color: #2271b1;
        background: #f6f7f7;
        vertical-align: top;
        display: inline-block;
        text-decoration: none;
        font-size: 13px;
        line-height: 2.15384615;
        min-height: 30px;
        margin: 0;
        padding: 0 10px;
        cursor: pointer;
        border-width: 1px;
        border-style: solid;
        -webkit-appearance: none;
        border-radius: 3px;
        white-space: nowrap;
        box-sizing: border-box;
    }
    .wp-btn-secondary:hover {
        background: #f0f0f1;
        border-color: #0a4b78;
        color: #0a4b78;
    }

    .wp-input {
        box-shadow: 0 0 0 transparent;
        border-radius: 4px;
        border: 1px solid #8c8f94;
        background-color: #fff;
        color: #2c3338;
        font-family: inherit;
        font-size: 14px;
        padding: 0 8px;
        line-height: 2;
        min-height: 30px;
    }
    .wp-input:focus {
        border-color: #2271b1;
        box-shadow: 0 0 0 1px #2271b1;
        outline: 2px solid transparent;
    }

    /* Modal Animation */
    @keyframes wp-modal-fade-in {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes wp-modal-zoom-in {
        from { transform: scale(0.95); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    #wp-media-modal:not(.hidden) {
        display: block !important;
        animation: wp-modal-fade-in 0.15s ease-out;
    }

    #wp-media-modal:not(.hidden) > div:last-child {
        animation: wp-modal-zoom-in 0.2s ease-out;
    }
    /* FINAL PREVIEW OVERRIDES - DO NOT MOVE */
    .is-preview.builder-wrapper,
    .builder-wrapper.is-preview {
        grid-template-columns: 0 1fr !important;
        grid-template-areas: 
            "topbar topbar"
            "sidebar canvas" !important;
        width: 100% !important;
    }
    .is-preview .builder-sidebar {
        display: none !important;
        width: 0 !important;
        height: 0 !important;
        opacity: 0 !important;
        pointer-events: none !important;
        position: absolute !important;
        left: -9999px !important;
    }
    .is-preview .builder-canvas-area {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        grid-area: auto !important;
    }
    .is-preview .canvas-container {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }
    /* Ensure container-row first-child margin removed in preview */
    .is-preview .container-row:first-child {
        margin-top: 0 !important;
    }
</style>
