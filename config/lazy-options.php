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
