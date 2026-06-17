<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class WorkflowTest extends TestCase
{
    private string $workflowPath;
    private string $content;

    protected function setUp(): void
    {
        $this->workflowPath = dirname(__DIR__)
            . DIRECTORY_SEPARATOR . '.github'
            . DIRECTORY_SEPARATOR . 'workflows'
            . DIRECTORY_SEPARATOR . 'tests.yml';

        $this->content = (string) file_get_contents($this->workflowPath);
    }

    public function testWorkflowFileExists(): void
    {
        $this->assertFileExists(
            $this->workflowPath,
            '.github/workflows/tests.yml must exist.'
        );
    }

    public function testWorkflowNameIsTests(): void
    {
        $this->assertStringContainsString(
            'name: Tests',
            $this->content,
            'Workflow name must be "Tests".'
        );
    }

    public function testWorkflowTriggersOnPush(): void
    {
        $this->assertStringContainsString(
            'push',
            $this->content,
            'Workflow must trigger on push.'
        );
    }

    public function testWorkflowTriggersOnPullRequest(): void
    {
        $this->assertStringContainsString(
            'pull_request',
            $this->content,
            'Workflow must trigger on pull_request.'
        );
    }

    public function testWorkflowTriggersOnWorkflowDispatch(): void
    {
        $this->assertStringContainsString(
            'workflow_dispatch',
            $this->content,
            'Workflow must trigger on workflow_dispatch.'
        );
    }

    public function testWorkflowIncludesPhp82(): void
    {
        $this->assertStringContainsString(
            '8.2',
            $this->content,
            'Workflow matrix must include PHP 8.2.'
        );
    }

    public function testWorkflowIncludesPhp83(): void
    {
        $this->assertStringContainsString(
            '8.3',
            $this->content,
            'Workflow matrix must include PHP 8.3.'
        );
    }

    public function testWorkflowRunsComposerTest(): void
    {
        $this->assertStringContainsString(
            'composer test',
            $this->content,
            'Workflow must run "composer test".'
        );
    }

    public function testWorkflowRunsComposerInstall(): void
    {
        $this->assertStringContainsString(
            'composer install',
            $this->content,
            'Workflow must run "composer install".'
        );
    }

    public function testWorkflowDoesNotDeploy(): void
    {
        $this->assertStringNotContainsStringIgnoringCase(
            'deploy',
            $this->content,
            'Workflow must not contain any deploy step.'
        );
    }

    public function testWorkflowDoesNotPublish(): void
    {
        $this->assertStringNotContainsStringIgnoringCase(
            'publish',
            $this->content,
            'Workflow must not contain any publish step.'
        );
    }

    public function testWorkflowDoesNotRequireSecrets(): void
    {
        $this->assertStringNotContainsString(
            'secrets.',
            $this->content,
            'Workflow must not reference any secrets.'
        );
    }
}
