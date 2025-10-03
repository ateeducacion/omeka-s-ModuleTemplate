<?php declare(strict_types=1);

namespace ModuleTemplateTest\Config;

use PHPUnit\Framework\TestCase;

class ModuleConfigTest extends TestCase
{
    public function testModuleConfigReturnsArrayWithDefaults(): void
    {
        // Require the config file which returns an array
        $config = require dirname(__DIR__, 3) . '/config/module.config.php';

        $this->assertIsArray($config);
        $this->assertArrayHasKey('ModuleTemplate', $config);
        $this->assertArrayHasKey('settings', $config['ModuleTemplate']);

        $settings = $config['ModuleTemplate']['settings'];
        $expectedKeys = [
            'activate_ModuleTemplate',
            'moduletemplate_demo_toggle',
            'moduletemplate_demo_text',
            'moduletemplate_demo_textarea',
            'moduletemplate_demo_number',
            'moduletemplate_demo_select',
            'moduletemplate_demo_color',
            'moduletemplate_demo_email',
            'moduletemplate_demo_url',
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $settings, "Missing default setting: $key");
        }
    }
}
