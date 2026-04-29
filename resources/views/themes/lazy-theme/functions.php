<?php

/**
 * Lazy Theme Functions & Hooks
 */

// Example hook: Filter site title
add_lazy_filter('site_title', function($title) {
    return $title . ' | Lazy Panda';
});

// Define Dashboard Option Pages via Hook
add_lazy_filter('cms_theme_options', function($options) {
    // Ad Management Settings
    $options['pages']['ad-settings'] = [
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
    ];

    // Social Media Settings
    $options['pages']['social-settings'] = [
        'title' => 'Social Media',
        'icon' => 'share',
        'group' => 'Marketing',
        'fields' => [
            'theme_social_facebook' => [
                'type' => 'text',
                'label' => 'Facebook URL',
            ],
            'theme_social_twitter' => [
                'type' => 'text',
                'label' => 'Twitter/X URL',
            ],
        ]
    ];
    return $options;
});

// Add more theme-specific logic here
