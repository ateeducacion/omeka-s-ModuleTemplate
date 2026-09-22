<?php
declare(strict_types=1);

namespace Laminas\EventManager;

interface SharedEventManagerInterface
{
    public function attach($id, $event, $callback, $priority = 1);
    public function detach($id, $event, $callback);
    public function getEvents($id);
    public function getListeners($id, $event);
    public function clearListeners($id, $event = null);
}
