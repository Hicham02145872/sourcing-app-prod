<?php

namespace App\Exceptions;

use Exception;

class WorkflowLimitReachedException extends Exception
{
    public const ERROR_CODE = 'WORKFLOW_LIMIT_REACHED';

    public function __construct(int $limit, int $currentCount)
    {
        parent::__construct(__('workflow.in_review_limit_reached', ['count' => $limit]), 0, null);
    }

    /**
     * Machine-readable error code exposed on 422 responses.
     */
    public function errorCode(): string
    {
        return self::ERROR_CODE;
    }
}