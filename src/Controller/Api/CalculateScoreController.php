<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Domain\Ad;
use App\Infrastructure\Persistence\InFileSystemPersistence;
use App\Manager\CalculateScoreManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/score")
 */
final class CalculateScoreController extends AbstractController
{
    /**
     * @Route("/calculate", name="calculate_score", methods={"GET"})
     */
    public function __invoke(CalculateScoreManager $calculateScoreManager): JsonResponse
    {
        $list = new InFileSystemPersistence;
        $ads = $list->getAds();
        $data = [];

        foreach ($ads as $ad){
            /** @var Ad $ad */
            $ad = $calculateScoreManager->calculateTotalScore($ad);

            $data[] = [
                'id' => $ad->getId(),
                'typology' => $ad->getTypology(),
                'description' => $ad->getDescription(),
                'pictures' => $ad->getPictures(),
                'houseSize' => $ad->getHouseSize(),
                'gardenSize' => $ad->getGardenSize(),
                'score' => $ad->getScore(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
