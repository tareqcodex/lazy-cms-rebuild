<?php

return [
    /*
    | 1. hooks (Inject into Existing Pages):
    |    Define fields here to inject them into standard CMS pages.
    |    Supported Keys (Hooks):
    |    - 'users-edit'       : User Edit Page
    |    - 'general-settings' : Main Settings Page
    |
    | 2. pages (Create New Standalone Pages):
    |    Define new administrative pages here. They will appear in the sidebar.
    |
    | 3. Field Configuration:
    |    - 'type'        : text, number, textarea, select, checkbox, image
    |    - 'label'       : Field title
    |    - 'desc'        : (Optional) Small italic description
    |    - 'placeholder' : (Optional) Input placeholder
    |    - 'default'     : (Optional) Default value
    |
    | 4. Frontend Usage (Retrieving Values):
    |    A. For Global Settings (Hooks & Custom Pages):
    |       Use: {{ get_cms_option('field_key', 'default_value') }}
    |       Example: {!! get_cms_option('header_ad_code') !!}
    |
    |    B. For Dynamic Custom Fields (ACPT):
    |       Use: {{ get_custom_field($post, 'field_name', 'default_value') }}
    |       Example: {{ get_custom_field($post, 'hero_subtitle') }}
    |
    |    C. Images:
    |       Example: <img src="{{ asset(get_cms_option('header_ad_banner')) }}">
    |
    */

    // Injections into existing pages
    
    // 'hooks' => [
    //     'general-settings' => [
    //         'fields' => [
    //             'languages' => [
    //                 'type' => 'select',
    //                 'label' => 'Languages',
    //                 'options' => [
    //                     'en' => 'English',
    //                     'bn' => 'Bangla',
    //                 ],
    //                 'default' => 'en'
    //             ]
    //         ]
    //     ],
     
    // ],

    // New standalone custom pages
    'pages' => [
        'ad-settings' => [
            'title' => 'Ad Management',
            'icon' => 'ads_click',
            'group' => 'Marketing',
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
            ]
        ],
    ]
];
