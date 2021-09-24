<?php

declare(strict_types=1);

class GitlabConfig
{
    private $gitlabUrl;
    private $apiVersion;

    private $privateToken;

    private $projectId;
    private $prodBranch;
    private $prodScheduleId;
    private $stageBranch;
    private $stageScheduleId;

    public function __construct(
        string $gitlabUrl,
        string $apiVersion,
        string $privateToken,
        int $projectId,
        string $prodBranch,
        int $prodScheduleId,
        string $stageBranch,
        int $stageScheduleId
    )
    {
        $this->gitlabUrl = $gitlabUrl;
        $this->apiVersion = $apiVersion;
        $this->privateToken = $privateToken;
        $this->projectId = $projectId;
        $this->prodBranch = $prodBranch;
        $this->prodScheduleId = $prodScheduleId;
        $this->stageBranch = $stageBranch;
        $this->stageScheduleId = $stageScheduleId;
    }

    public function getGitlabUrl(): string
    {
        return $this->gitlabUrl;
    }

    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    public function getPrivateToken(): string
    {
        return $this->privateToken;
    }

    public function getProjectId(): int
    {
        return $this->projectId;
    }

    public function getProdBranch(): string
    {
        return $this->prodBranch;
    }

    public function getProdScheduleId(): int
    {
        return $this->prodScheduleId;
    }

    public function getStageBranch(): string
    {
        return $this->stageBranch;
    }

    public function getStageScheduleId(): int
    {
        return $this->stageScheduleId;
    }
}