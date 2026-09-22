<?php

declare(strict_types=1);

namespace ModuleTemplateTest;

require dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . "/Support/Omeka/Module/AbstractModule.php";
require_once __DIR__ . "/Support/Omeka/Stdlib/Message.php";
require_once __DIR__ . "/Support/Omeka/Mvc/Controller/Plugin/Messenger.php";
require_once __DIR__ . "/Support/Laminas/Mvc/Controller/AbstractController.php";
require_once __DIR__ . "/Support/Laminas/EventManager/SharedEventManagerInterface.php";
require_once __DIR__ . "/Support/Laminas/View/Renderer/PhpRenderer.php";
require_once dirname(__DIR__) . "/Module.php";
