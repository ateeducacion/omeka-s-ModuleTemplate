<?php
declare(strict_types=1);

namespace ModuleTemplate;

//use ThreeDViewer\Media\FileRenderer\Viewer3DRenderer;

return [
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'form_elements' => [
        'invokables' => [
            Form\SettingsFieldset::class => Form\SettingsFieldset::class,
            Form\SiteSettingsFieldset::class => Form\SiteSettingsFieldset::class,
        ],
    ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => dirname(__DIR__) . '/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'ModuleTemplate' => [
        'settings' => [
            'activate_ModuleTemplate' => true,
            // Demo defaults so the form shows meaningful values
            'moduletemplate_demo_toggle' => false,
            'moduletemplate_demo_text' => 'Default text',
            'moduletemplate_demo_textarea' => "Line 1\nLine 2",
            'moduletemplate_demo_number' => 500,
            'moduletemplate_demo_select' => 'b',
            'moduletemplate_demo_color' => '#3366ff',
            'moduletemplate_demo_email' => 'demo@example.com',
            'moduletemplate_demo_url' => 'https://example.com',
        ]
    ],
];
