<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Dashboard Options
    |--------------------------------------------------------------------------
    |
    | Supported Field Types:
    | - 'text'     : Standard text input
    | - 'number'   : Numeric input
    | - 'textarea' : Large text area for codes/scripts
    | - 'select'   : Dropdown menu (requires 'options' array)
    | - 'checkbox' : Toggle switch (supports 'checkbox_label')
    | - 'image'    : Image upload field (with preview)
    |
    | Icon Support:
    | - Use Google Material Symbols names (e.g., 'settings', 'person', 'ads_click')
    |
    | Common Field Attributes:
    | - 'label'      : The field title shown on the page
    | - 'desc'       : (Optional) Small italic description below the field
    | - 'placeholder': (Optional) Placeholder text
    | - 'default'    : (Optional) Default value if not set
    |
    */

    'pages' => [
        'ad-settings' => [
            'title' => 'Ad Management',
            'icon' => 'ads_click',
            'group' => 'Settings',
            'fields' => [
                'header_ad_code' => [
                    'type' => 'textarea',
                    'label' => 'Header Ad Code',
                    'placeholder' => 'Paste your ad code here...',
                ],
                'header_ad_banner' => [
                    'type' => 'image',
                    'label' => 'Header Ad Banner',
                ],
                'header_ad_url' => [
                    'type' => 'text',
                    'label' => 'Header Ad URL',
                ],
                'header_ad_target' => [
                    'type' => 'checkbox',
                    'label' => 'Open in new tab',
                    'default' => '1'
                ],
            ]
        ],
    ]
];
