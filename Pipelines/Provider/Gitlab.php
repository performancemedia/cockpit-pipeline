<?php

declare(strict_types=1);

class Gitlab implements RepositoryProvider
{
    private $gitlabConfig;

    public function __construct(GitlabConfig $gitlabConfig)
    {
        $this->gitlabConfig = $gitlabConfig;
    }

    public function getPipelines(): array
    {
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => sprintf(
                    '%s/api/%s/projects/%d/pipelines',
                    $this->gitlabConfig->getgitlabUrl(),
                    $this->gitlabConfig->getApiVersion(),
                    $this->gitlabConfig->getProjectId()
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [sprintf('PRIVATE-TOKEN: %s', $this->gitlabConfig->getPrivateToken())],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        return $this->parseProject(json_decode($response, true, JSON_THROW_ON_ERROR));
    }

    public function runBranch(string $branch): void
    {
        $scheduleId = $branch === 'prod'
            ? $this->gitlabConfig->getProdScheduleId()
            : $this->gitlabConfig->getStageScheduleId();

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => sprintf(
                    '%s/api/%s/projects/%d/pipeline_schedules/%d/play',
                    $this->gitlabConfig->getgitlabUrl(),
                    $this->gitlabConfig->getApiVersion(),
                    $this->gitlabConfig->getProjectId(),
                    $scheduleId
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_HTTPHEADER => [sprintf('PRIVATE-TOKEN: %s', $this->gitlabConfig->getPrivateToken())],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true, JSON_THROW_ON_ERROR);

        if ($response['message'] !== '201 Created') {
            throw new Exception('Failed to run schedule, server returned: ' . $response['message']);
        }
    }

    private function parseProject(array $response): array
    {
        $ret = [];

        foreach ($response as $row) {
            $row['created_at'] = (new \DateTimeImmutable($row['created_at']))->format('Y-M-d H:i:s');
            $row['updated_at'] = (new \DateTimeImmutable($row['updated_at']))->format('Y-M-d H:i:s');

            $ret[] = $row;
        }

        return $ret;
    }
}
