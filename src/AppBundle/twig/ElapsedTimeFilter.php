<?php
/**
 * Created by PhpStorm.
 * User: formation
 * Date: 08/09/2017
 * Time: 16:11
 */

namespace AppBundle\twig;


class ElapsedTimeFilter extends \Twig_Extension
{
    private $intervarFormat = [
        "y" => "an",
        "m" => "mois",
        "d" => "jour",
        "h" => "heure",
        "i" => "minute",
        "s" => "seconde"
    ];


    public function getName()
    {
        return "elapsedTimeFilter";
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter("elapsed", [$this, "elapsed"])
        );
    }

    public function elapsed($date)
    {
        $now = new \DateTime();

        $interval = $now->diff($date);

        $format = "";
        foreach ($this->intervarFormat as $key=> $val){
            $value = $interval->$key;
            if($value>0){
                $format .= "%{$key} $val ";
            }
        }

        return $interval->format($format);


    }
}