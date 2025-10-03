<?php declare(strict_types=1);

namespace ModuleTemplateTest\Form;

use PHPUnit\Framework\TestCase;
use ModuleTemplate\Form\ConfigForm;
use Laminas\Form\Element;

class ConfigFormFieldsTest extends TestCase
{
    public function testFormContainsAllDemoFields(): void
    {
        $form = new ConfigForm();
        $form->init();

        $expected = [
            'moduletemplate_demo_toggle' => Element\Checkbox::class,
            'moduletemplate_demo_text' => Element\Text::class,
            'moduletemplate_demo_textarea' => Element\Textarea::class,
            'moduletemplate_demo_number' => Element\Number::class,
            'moduletemplate_demo_select' => Element\Select::class,
            'moduletemplate_demo_color' => Element\Color::class,
            'moduletemplate_demo_email' => Element\Email::class,
            'moduletemplate_demo_url' => Element\Url::class,
        ];

        foreach ($expected as $name => $type) {
            $this->assertTrue($form->has($name), "Form should contain element '$name'");
            $element = $form->get($name);
            $this->assertInstanceOf($type, $element, "Element '$name' should be of type $type");
        }
    }

    public function testCheckboxUsesHiddenValues(): void
    {
        $form = new ConfigForm();
        $form->init();
        /** @var Element\Checkbox $el */
        $el = $form->get('moduletemplate_demo_toggle');
        $opts = $el->getOptions();
        $this->assertSame('1', $opts['checked_value'] ?? null);
        $this->assertSame('0', $opts['unchecked_value'] ?? null);
    }

    public function testSelectHasExpectedOptions(): void
    {
        $form = new ConfigForm();
        $form->init();
        /** @var Element\Select $el */
        $el = $form->get('moduletemplate_demo_select');
        $options = $el->getValueOptions();
        $this->assertArrayHasKey('a', $options);
        $this->assertArrayHasKey('b', $options);
        $this->assertArrayHasKey('c', $options);
    }
}
