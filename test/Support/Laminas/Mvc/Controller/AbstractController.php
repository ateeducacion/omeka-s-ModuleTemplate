<?php
declare(strict_types=1);

namespace Laminas\Mvc\Controller;

abstract class AbstractController
{
    public function params()
    {
        return new class {
            public function fromPost($key = null, $default = null)
            {
                if ($key === null) {
                    return $_POST ?? [];
                }
                return $_POST[$key] ?? $default;
            }

            public function fromQuery($key = null, $default = null)
            {
                if ($key === null) {
                    return $_GET ?? [];
                }
                return $_GET[$key] ?? $default;
            }
        };
    }
}
