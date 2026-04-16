<?php

function moneyFormat($number){
       if($number >= 1000000){
          return round($number/100000,1).'M';
       } elseif($number >= 1000){
        return round($number/1000,1).'K';
       }

       return $number;
}
