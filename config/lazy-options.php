<?php

return [
    'hooks' => [
        'general-settings' => [
            'fields' => []
        ],
        'theme-options' => [
            'fields' => [
                'branding_section' => [
                    'type'  => 'title',
                    'label' => 'Site Branding',
                ],
                'theme_logo' => [
                    'type'  => 'media',
                    'label' => 'Site Logo',
                ],
                'theme_favicon' => [
                    'type'  => 'media',
                    'label' => 'Site Favicon',
                ],
                'footer_copyright' => [
                    'type'    => 'text',
                    'label'   => 'Footer Copyright Text',
                    'default' => '© ' . date('Y') . ' Lazy CMS. All rights reserved.',
                ],
                'footer_section' => [
                    'type'  => 'title',
                    'label' => 'Footer & Contact Info',
                ],
                'footer_about' => [
                    'type'    => 'textarea',
                    'label'   => 'Footer About Text',
                    'default' => 'A minimalist, Astra-inspired theme for Lazy CMS. Clean, fast, and professional design focusing on readability and content delivery.',
                ],
                'contact_email' => [
                    'type'    => 'text',
                    'label'   => 'Contact Email',
                    'default' => 'hello@lazypanda.com',
                ],
                'contact_address' => [
                    'type'    => 'text',
                    'label'   => 'Contact Address',
                    'default' => '123 CMS Street, Web City, WP 101',
                ],
                'social_facebook' => [
                    'type'  => 'text',
                    'label' => 'Facebook URL',
                ],
                'social_twitter' => [
                    'type'  => 'text',
                    'label' => 'Twitter URL',
                ],
                'social_instagram' => [
                    'type'  => 'text',
                    'label' => 'Instagram URL',
                ],
                'social_linkedin' => [
                    'type'  => 'text',
                    'label' => 'LinkedIn URL',
                ],
                'performance_section' => [
                    'type'  => 'title',
                    'label' => 'Media & Image Optimization',
                ],
                'enable_page_cache' => [
                    'type'          => 'checkbox',
                    'label'         => 'Static Caching',
                    'checkbox_label'=> 'Enable response caching for frontend',
                    'desc'          => 'Drastically improves speed by caching HTML output. Cache is cleared when you save settings or update content.',
                    'default'       => '0',
                ],
                'image_auto_webp' => [
                    'type'          => 'checkbox',
                    'label'         => 'WebP Conversion',
                    'checkbox_label'=> 'Auto convert uploaded images to WebP',
                    'desc'          => 'Recommended for better performance and smaller file sizes.',
                    'default'       => '1',
                ],
                'image_quality' => [
                    'type'    => 'number',
                    'label'   => 'Image Quality',
                    'default' => '80',
                    'desc'    => '(0-100) Lower quality means smaller file sizes. 80 is recommended.',
                ],
                'image_max_width' => [
                    'type'    => 'number',
                    'label'   => 'Max Image Width',
                    'default' => '1920',
                    'desc'    => 'Pixels. Images wider than this will be automatically resized. 1920 is default.',
                ],
            ]
        ],
    ],
    'pages' => []
];
