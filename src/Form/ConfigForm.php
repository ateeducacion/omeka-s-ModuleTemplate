<?php
declare(strict_types=1);

namespace ModuleTemplate\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ConfigForm extends Form
{
    public function init(): void
    {
        // Simple demo toggle
        $this->add([
            'name' => 'moduletemplate_demo_toggle',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'DEMO: enable feature', // @translate
                'info' => 'Demo field to test checkbox behavior.', // @translate
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
            'attributes' => [
                'value' => '0',
            ],
        ]);

        // Text input
        $this->add([
            'name' => 'moduletemplate_demo_text',
            'type' => Element\Text::class,
            'options' => [
                'label' => 'DEMO: free text', // @translate
                'info' => 'Demo field to test single-line input.', // @translate
            ],
            'attributes' => [
                'placeholder' => 'A demo text…',
            ],
        ]);

        // Textarea
        $this->add([
            'name' => 'moduletemplate_demo_textarea',
            'type' => Element\Textarea::class,
            'options' => [
                'label' => 'DEMO: textarea', // @translate
                'info' => 'Demo field to test multi-line input.', // @translate
            ],
            'attributes' => [
                'rows' => 4,
            ],
        ]);

        // Number
        $this->add([
            'name' => 'moduletemplate_demo_number',
            'type' => Element\Number::class,
            'options' => [
                'label' => 'DEMO: number (px)', // @translate
                'info' => 'Demo field to test numeric input.', // @translate
            ],
            'attributes' => [
                'min' => 0,
                'max' => 2000,
                'step' => 1,
            ],
        ]);

        // Select
        $this->add([
            'name' => 'moduletemplate_demo_select',
            'type' => Element\Select::class,
            'options' => [
                'label' => 'DEMO: select', // @translate
                'info' => 'Demo field to test dropdown selection.', // @translate
                'value_options' => [
                    'a' => 'Option A', // @translate
                    'b' => 'Option B', // @translate
                    'c' => 'Option C', // @translate
                ],
            ],
        ]);

        // Color
        $this->add([
            'name' => 'moduletemplate_demo_color',
            'type' => Element\Color::class,
            'options' => [
                'label' => 'DEMO: color', // @translate
                'info' => 'Demo field to test color picker.', // @translate
            ],
        ]);

        // Email
        $this->add([
            'name' => 'moduletemplate_demo_email',
            'type' => Element\Email::class,
            'options' => [
                'label' => 'DEMO: email address', // @translate
                'info' => 'Demo field to test email input.', // @translate
            ],
        ]);

        // URL
        $this->add([
            'name' => 'moduletemplate_demo_url',
            'type' => Element\Url::class,
            'options' => [
                'label' => 'DEMO: URL', // @translate
                'info' => 'Demo field to test URL input.', // @translate
            ],
        ]);
    }
}
