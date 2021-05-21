<?php

declare(strict_types=1);

namespace PmPipelines\Controller;

class Admin extends \Cockpit\AuthController
{
    public function index()
    {
        if ($this->app->request->server['REQUEST_METHOD'] === 'GET') {
            return $this->get();
        } elseif ($this->app->request->server['REQUEST_METHOD'] === 'POST') {
            return $this->post();
        }
    }

    private function get()
    {
        if ($file = $this->app->path('#storage:pipelines.json')) {
            $content = @file_get_contents($file);
        }

        $credentials = json_decode(
            $content,
            true,
            JSON_THROW_ON_ERROR
        );

        $response = json_decode(
            $this->getProject($credentials['project_id'], $credentials['private_token']),
            true,
            JSON_THROW_ON_ERROR
        );

        return $this->render('pmpipelines:views/index.php', ['pipelines' => $response]);
    }

    private function post()
    {
        if ($file = $this->app->path('#storage:pipelines.json')) {
            $content = @file_get_contents($file);
        }

        $credentials = json_decode(
            $content,
            true,
            JSON_THROW_ON_ERROR
        );

        $this->runPipeline($credentials['project_id'], $credentials['ref'], $credentials['trigger_token']);

        return $this->get();
    }

    private function getProject(int $projectId, string $privateToken)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => sprintf('https://gitlab.performance-media.pl/api/v4/projects/%d/pipelines', $projectId),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [
                    sprintf('PRIVATE-TOKEN: %s', $privateToken)
                ],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    private function runPipeline(int $projectId, string $ref, string $triggerToken)
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => sprintf(
                    'https://gitlab.performance-media.pl/api/v4/projects/%d/trigger/pipeline',
                    $projectId
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => ['token' => $triggerToken, 'ref' => $ref],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}