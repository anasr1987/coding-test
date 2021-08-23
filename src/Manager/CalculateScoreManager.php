<?php

declare(strict_types=1);

namespace App\Manager;

use App\Domain\Ad;
use App\Domain\Picture;
use App\Infrastructure\Persistence\InFileSystemPersistence;

final class CalculateScoreManager
{

    /**
     * @param Ad $ad
     * @return Ad
     */
    public function calculateTotalScore($ad)
    {
        $scoreByPhoto = $this->calculateScoreByPhoto($ad);
        $scoreByDescription = $this->calculateScoreByDescription($ad);
        $scoreByCompleted = $this->calculateScoreByCompleted($ad);

        $totalScore = $scoreByPhoto + $scoreByDescription + $scoreByCompleted;

        $ad->setScore($totalScore);

        return $ad;
    }

    public function calculateScoreByPhoto($ad)
    {
        $score = 0;
        $list = new InFileSystemPersistence;

        //Si el anuncio no tiene ninguna foto se restan 10 puntos
        /** @var Ad $ad */
        if(empty($ad->getPictures())){
            $score += -10;
            return $score;
        }

        $picturesByAd = $list->getPicturesByAd($ad);

        /** @var Picture $picture */
        foreach ($picturesByAd as $picture){

            //Cada foto que tenga el anuncio proporciona 20 puntos si es una foto de alta resolución (HD)
            if ($picture->getQuality() == 'HD'){
                $score += 20;

            //Cada foto que tenga el anuncio proporciona 10 puntos si NO es una foto de alta resolución (HD)
            } else {
                $score += 10;
            }
        }
        return $score;
    }

    public function calculateScoreByDescription($ad)
    {
        $score = 0;

        //Que el anuncio tenga un texto descriptivo suma 5 puntos.
        /** @var Ad $ad */
        if(count($this->sanitize_words($ad->getDescription())) > 0){
            $score += 5;
        } else {
            return $score;
        }

        $score += $this->calculateScoreDescriptionByNumberOfWords($ad);
        $score += $this->calculateScoreDescriptionByWords($ad);

        return $score;
    }

    public function calculateScoreByCompleted($ad)
    {
        $score = 0;

        /** @var Ad $ad */
        switch ($ad->getTypology()){

            case 'CHALET':
                //tener descripción, al menos una foto y tamaño de vivienda y de jardín
                if(count($this->sanitize_words($ad->getDescription())) > 0 && !empty($ad->getPictures()) && $ad->getHouseSize() && $ad->getGardenSize()){
                    $score += 40;
                }
                break;

            case 'FLAT':
                //tener descripción, al menos una foto y también tamaño de vivienda
                if(count($this->sanitize_words($ad->getDescription())) > 0 && !empty($ad->getPictures()) && $ad->getHouseSize()){
                    $score += 40;
                }
                break;

            case 'GARAGE':
                //tener al menos una foto y también tamaño
                if(!empty($ad->getPictures()) && $ad->getHouseSize()){
                    $score += 40;
                }
                break;
        }
        return $score;
    }

    public function calculateScoreDescriptionByNumberOfWords($ad)
    {
        $score = 0;

        /** @var Ad $ad */
        switch ($ad->getTypology()){

            case 'CHALET':
                //En el caso de los chalets, si tiene mas de 50 palabras, añade 20 puntos.
                if(count($this->sanitize_words($ad->getDescription())) > 50){
                    $score += 20;
                }
                break;

            case 'FLAT':
                //En el caso de los pisos, la descripción aporta 10 puntos si tiene entre 20 y 49 palabras
                if(count($this->sanitize_words($ad->getDescription())) >= 20 && count($this->sanitize_words($ad->getDescription())) <= 49){
                    $score += 10;
                //30 puntos si tiene 50 o mas palabras.
                } elseif (count($this->sanitize_words($ad->getDescription())) >= 50) {
                    $score += 30;
                }
                break;
        }
        return $score;
    }

    public function calculateScoreDescriptionByWords($ad)
    {
        $score = 0;

        /** @var Ad $ad */
        //Que las siguientes palabras aparezcan en la descripción añaden 5 puntos cada una: Luminoso, Nuevo, Céntrico, Reformado, Ático.

        //Luminoso
        if(mb_substr_count(mb_strtolower($ad->getDescription()), 'luminoso')){
            $score += 5;
        }

        //Nuevo
        if(mb_substr_count(mb_strtolower($ad->getDescription()), 'nuevo')){
            $score += 5;
        }

        //Céntrico
        if(mb_substr_count(mb_strtolower($ad->getDescription()), 'céntrico')){
            $score += 5;
        }

        //Reformado
        if(mb_substr_count(mb_strtolower($ad->getDescription()), 'reformado')){
            $score += 5;
        }

        //Ático
        if(mb_substr_count(mb_strtolower($ad->getDescription()), 'ático')){
            $score += 5;
        }

        return $score;
    }

    function sanitize_words($string) {
        preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u",$string,$matches,PREG_PATTERN_ORDER);
        return $matches[0];
    }
}
