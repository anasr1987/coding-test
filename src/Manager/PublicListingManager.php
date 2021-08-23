<?php

declare(strict_types=1);

namespace App\Manager;

use Symfony\Component\HttpFoundation\JsonResponse;

final class PublicListingManager
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
