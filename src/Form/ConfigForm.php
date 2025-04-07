<?php
declare(strict_types=1);

namespace IsolatedSites\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class ConfigForm extends Form
{
    /**
     * Initialize the form elements.
     */
    public function init(): void
    {
        $this->add([
            'name' => 'activate_IsolatedSites_cb',
            'type' => Element\Checkbox::class,
            'options' => [
                'label' => 'Enable this option to hide unallowed sites ',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0',
            ],
            'attributes' => [
                'required' => false,
                'id' => 'activate_IsolatedSites_cb'
            ],
        ]);
    }
}
