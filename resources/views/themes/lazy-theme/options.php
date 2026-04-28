<?php

/**
 * Theme Options and Functions
 * This file acts like WordPress functions.php
 * You can define your theme-specific logic and dashboard option pages here.
 */

config([
    'lazy-options' => [
        /*
        | 2. pages (Create New Standalone Pages):
        |    Define new administrative pages here. They will appear in the sidebar.
        */
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
            
            'theme-settings' => [
                'title' => 'Theme Settings',
                'icon' => 'palette',
                'group' => 'Theme',
                'fields' => [
                    'theme_logo' => [
                        'type' => 'media',
                        'label' => 'Theme Logo',
                    ],
                    'footer_copyright' => [
                        'type' => 'text',
                        'label' => 'Footer Copyright Text',
                        'default' => '© 2024 Lazy CMS. All rights reserved.',
                    ],
                ]
            ],
        ]
    ]
]);
