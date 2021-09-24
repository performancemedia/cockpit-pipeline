<?php

declare(strict_types=1);

interface RepositoryProvider
{
    public function getPipelines(): array;
    public function runBranch(string $branch): void;
}