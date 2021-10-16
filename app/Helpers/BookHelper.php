<?php

namespace App\Helpers;

class BookHelper
{

    static function cleanTitle(string $title){


        $title = mb_strtolower($title);

        $title = str_replace("рассказы", "", $title);
        $title = str_replace("рассказ", "", $title);
        $title = str_replace("фантастич повесть", "", $title);
        $title = str_replace("повесть", "", $title);
        $title = str_replace("повести", "", $title);
        $title = str_replace("книга для чтения на англ яз", "", $title);
        $title = str_replace("романы", "", $title);
        $title = str_replace("роман", "", $title);
        $title = str_replace("перевод с английского", "", $title);
        $title = str_replace("пер. с англ.", "", $title);
        $title = str_replace("графический роман", "", $title);
        $title = str_replace("комикс", "", $title);
        $title = str_replace("сборник", "", $title);
        $title = str_replace("сказкапритча", "", $title);
        $title = str_replace("сказка", "", $title);
        $title = str_replace("сказки", "", $title);
        $title = str_replace("пер с фр", "", $title);
        $title = str_replace("-", "", $title);
        $title = str_replace("аудиокнига", "", $title);
        $title = str_replace("фантастические", "", $title);
        $title = str_replace("для среднего школьного возраста", "", $title);
        $title = str_replace("для старшего школьного возраста", "", $title);
        $title = str_replace("перевод с французского", "", $title);

        $title = preg_replace("/\([^)]+\)/","",$title);

        $title= preg_replace('~[^\p{Cyrillic}a-z0-9_\s-]+~ui', '', $title);
        $title = preg_replace('/\s\s+/', ' ', $title);

        $title = trim($title, " ");


        return $title;
    }
}
