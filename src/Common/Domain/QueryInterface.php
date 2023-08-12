<?php

namespace Src\Common\Domain;

use Src\Common\Domain\Exceptions\UnauthorizedUserException;

interface QueryInterface
{
    /**
     * @throws UnauthorizedUserException
     */
    public function handle(array $filters = [], ?int $perPage = null): mixed;
}
