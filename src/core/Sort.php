<?php

namespace App;

class Sort
{

    public static function binarySearch($value, $items, $start = 0, $end = null)
    {
        // Si la valeur de $end est nulle, elle prends la valeur de l'index le plus grand dans le tableau $items
        if ($end === null) {
            $end = count($items) - 1;
        }

        // Si la valeur de $start est supérieur à la valeur de $end (incohérent)
        // Retourner -1 pour indiquer que l'index n'a pas été trouvé
        if ($start > $end) {
            return -1;
        }

        // Chercher l'index le plus au milieu dans l'intervalle entre $start et $end
        $index = floor($start + $end / 2);

        // Si la valeur qui est à l'index le plus au milieu est égal à la valeur recherchée
        // Retourner l'index le plus au milieu
        if($items[$index] === $value){
            return $index;
        }

        // Si la valeur à chercher est supérieure à la valeur à l'index du milieu
        // Appeler récursivement BinarySearch::search, en partant de l'index du milieu + 1 et jusqu'à la fin
        if($value > $items[$index]){
            return Sort::binarySearch($value, $items, $index++, $end);
        }else{
            // Appeler récursivement BinarySearch::search, en partant du précédent index de départ jusqu'avant le milieu (-1)
            return Sort::binarySearch($value, $items, $start, $index--);
        }

    }
}
