<?php

declare(strict_types=1);

namespace ModuleTemplateTest;

use ModuleTemplate\Module;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\Mvc\Controller\AbstractController;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    private $settings;
    private $services;
    private $module;

    protected function setUp(): void
    {
        $this->settings = new class {
            public $values = [];

            public function get($key, $default = null)
            {
                return $this->values[$key] ?? $default;
            }

            public function set($key, $value): void
            {
                $this->values[$key] = $value;
            }
        };
        $this->services = new ServiceManager(['services' => [
            'Omeka\Settings' => $this->settings,
            'Config' => [],
        ]]);
        $this->module = new Module();
        $this->module->setServiceLocator($this->services);
    }

    public function testConfigurationAndLifecycle(): void
    {
        $this->assertSame(include dirname(__DIR__, 2) . '/config/module.config.php', $this->module->getConfig());
        $this->module->install($this->services);
        $this->module->uninstall($this->services);
        $this->assertSame([], $this->settings->values);
    }

    public function testFormUsesSavedValuesAndDefaults(): void
    {
        $renderer = new class extends PhpRenderer {
            public $form;

            public function formCollection($form, $wrap)
            {
                $this->form = $form;
                return 'rendered form';
            }
        };
        $this->assertSame('rendered form', $this->module->getConfigForm($renderer));
        $this->assertSame('Default text', $renderer->form->get('moduletemplate_demo_text')->getValue());
        $this->settings->values['moduletemplate_demo_text'] = 'Saved';
        $this->module->getConfigForm($renderer);
        $this->assertSame('Saved', $renderer->form->get('moduletemplate_demo_text')->getValue());
    }

    public function testSubmissionPersistsSettings(): void
    {
        $post = ['moduletemplate_demo_text' => 'Saved', 'moduletemplate_demo_toggle' => '1',
            'moduletemplate_demo_number' => '42'];
        $controller = $this->getMockBuilder(AbstractController::class)
            ->disableOriginalConstructor()->onlyMethods(['params'])->getMock();
        $params = new class ($post) {
            private $post;

            public function __construct(array $post)
            {
                $this->post = $post;
            }

            public function fromPost()
            {
                return $this->post;
            }
        };
        $controller->method('params')->willReturn($params);
        $this->module->handleConfigForm($controller);
        $this->assertSame('Saved', $this->settings->values['moduletemplate_demo_text']);
        $this->assertTrue($this->settings->values['moduletemplate_demo_toggle']);
        $this->assertSame(42, $this->settings->values['moduletemplate_demo_number']);
        $this->assertSame(0, $this->settings->values['activate_ModuleTemplate']);
    }
}
