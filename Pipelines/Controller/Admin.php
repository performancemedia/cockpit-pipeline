<?php

declare(strict_types=1);

namespace Pipelines\Controller;

use GitlabConfig;

class Admin extends \Cockpit\AuthController
{
    private $gitlab;

    public function __construct($app)
    {
        parent::__construct($app);

        if ($file = $this->app->path('#storage:pipelines.json')) {
            $content = @file_get_contents($file);
            $content = stripslashes($content);
        } else {
            throw new \Exception('No pipelines.json config found');
        }

        $credentials = json_decode(
            $content,
            true,
            JSON_THROW_ON_ERROR
        );

        $this->gitlab = new \Gitlab(
            new GitlabConfig(
                $credentials['gitlab_url'],
                $credentials['api_version'],
                $credentials['private_token'],
                $credentials['project_id'],
                $credentials['ref']['prod']['branch'],
                $credentials['ref']['prod']['schedule_id'],
                $credentials['ref']['stage']['branch'],
                $credentials['ref']['stage']['schedule_id'],
            )
        );
    }

    /**
     * Route: GET <cockpit_url>/pipelines/index
     */
    public function index()
    {
        if ($this->app->request->server['REQUEST_METHOD'] === 'GET') {
            return $this->get();
        } elseif ($this->app->request->server['REQUEST_METHOD'] === 'POST') {
            return $this->post($this->app->request->server['QUERY_STRING']);
        }
    }

    private function get()
    {
        $response = $this->gitlab->getPipelines();

        return $this->render('pipelines:views/index.php', ['pipelines' => $response]);
    }

    /**
     * Route: POST <cockpit_url>/pipelines/index
     */
    private function post(string $branch)
    {
        $this->gitlab->runBranch($branch);

        return $this->get();
    }
}
