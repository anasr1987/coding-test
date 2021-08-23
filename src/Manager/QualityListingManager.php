<?php

declare(strict_types=1);

namespace App\Manager;

use Symfony\Component\HttpFoundation\JsonResponse;

final class QualityListingManager
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
