<?php

/**
 * Main CMS Options Configuration
 * Note: Theme-specific options are now managed in the theme's options.php file.
 * This file is kept as a fallback or for global core settings.
 */

return [
    'hooks' => [
        'general-settings' => [
            'fields' => [
                'branding_section' => [
                    'type' => 'title',
                    'label' => 'Site Branding',
                ],
                'theme_logo' => [
                    'type' => 'media',
                    'label' => 'Site Logo',
                ],
                'theme_favicon' => [
                    'type' => 'media',
                    'label' => 'Site Favicon',
                ],
                'footer_copyright' => [
                    'type' => 'text',
                    'label' => 'Footer Copyright Text',
                    'default' => '© 2024 Lazy CMS. All rights reserved.',
                ],
                'footer_section' => [
                    'type' => 'title',
                    'label' => 'Footer & Contact Info',
                ],
                'footer_about' => [
                    'type' => 'textarea',
                    'label' => 'Footer About Text',
                    'default' => 'A minimalist, Astra-inspired theme for Lazy CMS. Clean, fast, and professional design focusing on readability and content delivery.',
                ],
                'contact_email' => [
                    'type' => 'text',
                    'label' => 'Contact Email',
                    'default' => 'hello@lazypanda.com',
                ],
                'contact_address' => [
                    'type' => 'text',
                    'label' => 'Contact Address',
                    'default' => '123 CMS Street, Web City, WP 101',
                ],
                'social_facebook' => [
                    'type' => 'text',
                    'label' => 'Facebook URL',
                ],
                'social_twitter' => [
                    'type' => 'text',
                    'label' => 'Twitter URL',
                ],
                'social_instagram' => [
                    'type' => 'text',
                    'label' => 'Instagram URL',
                ],
                'social_linkedin' => [
                    'type' => 'text',
                    'label' => 'LinkedIn URL',
                ],
                'media_optimization' => [
                    'type' => 'title',
                    'label' => 'Media & Image Optimization',
                ],
                'image_auto_webp' => [
                    'type' => 'checkbox',
                    'label' => 'Auto convert to WebP',
                    'default' => '1',
                ],
                'image_quality' => [
                    'type' => 'number',
                    'label' => 'Image Quality (0-100)',
                    'default' => '80',
                ],
                'image_max_width' => [
                    'type' => 'number',
                    'label' => 'Max Image Width (px)',
                    'default' => '1920',
                ],
            ]
        ]
    ],
    'pages' => []
];
