<?php
declare(strict_types=1);

namespace ModuleTemplate;

use Laminas\EventManager\Event;
use Laminas\EventManager\SharedEventManagerInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\Mvc\Controller\AbstractController;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Module\AbstractModule;
use Omeka\Mvc\Controller\Plugin\Messenger;
use Omeka\Stdlib\Message;
use ModuleTemplate\Form\ConfigForm;

/**
 * Main class for the module.
 */
class Module extends AbstractModule
{
    /**
     * Retrieve the configuration array.
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Execute logic when the module is installed.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function install(ServiceLocatorInterface $serviceLocator)
    {
        $messenger = new Messenger();
        $message = new Message("ModuleTemplate module installed.");
        $messenger->addSuccess($message);
    }
    /**
     * Execute logic when the module is uninstalled.
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function uninstall(ServiceLocatorInterface $serviceLocator)
    {
        $messenger = new Messenger();
        $message = new Message("ModuleTemplate module uninstalled.");
        $messenger->addWarning($message);
    }
    
    /**
     * Register the file validator service and renderers.
     *
     * @param SharedEventManagerInterface $sharedEventManager
     */
    public function attachListeners(SharedEventManagerInterface $sharedEventManager): void
    {
        // Replace the default file validator with our custom one
    }
    
    /**
     * Get the configuration form for this module.
     *
     * @param PhpRenderer $renderer
     * @return string
     */
    public function getConfigForm(PhpRenderer $renderer)
    {
        $services = $this->getServiceLocator();
        $config = $services->get('Config');
        $settings = $services->get('Omeka\Settings');
        
        $form = new ConfigForm();
        $form->init();

        // Seed form fields with saved settings or defaults
        $form->setData([
            'activate_ModuleTemplate_cb' => $settings->get('activate_ModuleTemplate', 1),
            'moduletemplate_demo_toggle' => $settings->get('moduletemplate_demo_toggle', false) ? '1' : '0',
            'moduletemplate_demo_text' => $settings->get('moduletemplate_demo_text', 'Default text'),
            'moduletemplate_demo_textarea' => $settings->get('moduletemplate_demo_textarea', "Line 1\nLine 2"),
            'moduletemplate_demo_number' => $settings->get('moduletemplate_demo_number', 500),
            'moduletemplate_demo_select' => $settings->get('moduletemplate_demo_select', 'b'),
            'moduletemplate_demo_color' => $settings->get('moduletemplate_demo_color', '#3366ff'),
            'moduletemplate_demo_email' => $settings->get('moduletemplate_demo_email', 'demo@example.com'),
            'moduletemplate_demo_url' => $settings->get('moduletemplate_demo_url', 'https://example.com'),
        ]);
        
        return $renderer->formCollection($form, false);
    }
    
    /**
     * Handle the configuration form submission.
     *
     * @param AbstractController $controller
     */
    public function handleConfigForm(AbstractController $controller)
    {
        $services = $this->getServiceLocator();
        $settings = $services->get('Omeka\Settings');
        
        $config = $controller->params()->fromPost();

        $value = isset($config['activate_ModuleTemplate_cb']) ? $config['activate_ModuleTemplate_cb'] : 0;
        $settings->set('activate_ModuleTemplate', $value);

        // Persist demo settings (cast/basic normalization)
        $settings->set(
            'moduletemplate_demo_toggle',
            isset($config['moduletemplate_demo_toggle'])
                && $config['moduletemplate_demo_toggle'] === '1'
        );
        $settings->set('moduletemplate_demo_text', (string)($config['moduletemplate_demo_text'] ?? ''));
        $settings->set('moduletemplate_demo_textarea', (string)($config['moduletemplate_demo_textarea'] ?? ''));
        if (isset($config['moduletemplate_demo_number']) && is_numeric($config['moduletemplate_demo_number'])) {
            $settings->set('moduletemplate_demo_number', (int)$config['moduletemplate_demo_number']);
        }
        $settings->set('moduletemplate_demo_select', (string)($config['moduletemplate_demo_select'] ?? ''));
        $settings->set('moduletemplate_demo_color', (string)($config['moduletemplate_demo_color'] ?? ''));
        $settings->set('moduletemplate_demo_email', (string)($config['moduletemplate_demo_email'] ?? ''));
        $settings->set('moduletemplate_demo_url', (string)($config['moduletemplate_demo_url'] ?? ''));
    }
    
    // /**
}
