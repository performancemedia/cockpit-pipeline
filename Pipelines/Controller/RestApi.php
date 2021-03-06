<?php

declare(strict_types=1);

namespace Pipelines\Controller;

class RestApi extends \LimeExtra\Controller {

    public function get($name = null) {

        $content = '{}';

        if ($file = $this->app->path('#storage:pipelines.json')) {
            $content = @file_get_contents($file);
        }

        $json = json_decode($content, true);

        if (!$json) {
            $json = [];
        }

        $components = new \ArrayObject($json);

        return $name ? (isset($components[$name]) ? $components[$name] : null) : $components->getArrayCopy();
    }

    public function store(): bool
    {

        if ($components = $this->param('components')) {
            $this->helper('fs')->write('#storage:pipelines.json', json_encode($components, JSON_PRETTY_PRINT));
            return true;
        }

        return false;
    }

}
