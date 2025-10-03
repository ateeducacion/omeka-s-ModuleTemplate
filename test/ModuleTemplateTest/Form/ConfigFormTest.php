<?php declare(strict_types=1);

namespace ModuleTemplateTest\Form;

use PHPUnit\Framework\TestCase;
use ModuleTemplate\Form\ConfigForm;
use Laminas\Form\Element;
use Laminas\Form\Form;

class ConfigFormTest extends TestCase
{
    private $form;
    
    public function setUp(): void
    {
        $this->form = new ConfigForm();
    }
    
    public function testFormCanBeInstantiated(): void
    {
        $this->assertInstanceOf(ConfigForm::class, $this->form);
        $this->assertInstanceOf(Form::class, $this->form);
    }
}
