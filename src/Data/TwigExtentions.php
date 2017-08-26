<?php

namespace  Quizz\Data;
use Twig_Extension;

class TwigExtentions
{
public static $categories =
    [
        "A1"=>"безопасная эксплуатация",
        "A2"=>"безопасная эксплуатация",
        "A3"=>"безопасная эксплуатация",
        "A4"=>"безопасная эксплуатация.",
        "B1"=>"безопасная эксплуатация",
        "B2"=>"ракторист-машинист",
        "C1"=>"безопасная эксплуатация",
        "C2"=>"тракторист-машинист.",
        "D1"=>"безопасная эксплуатация",
        "D2"=>"тракторист-машинист",
        "E1"=>"безопасная эксплуатация",
        "E2"=>"тракторист-машинист",
        "F1"=>"тракторист-машинист",
        "F2"=>"тракторист-машинист",
        "P1"=>"ПДД для всех категорий самоходных машин",
    ];


public static function nameIt($shortName)
{

    if(isset(TwigExtentions::$categories[$shortName]) && null !== $name = TwigExtentions::$categories[$shortName])
    {
        return $name;
    }
    else
    {
        return "Категория ".easyName($shortName);
    }
}

public static function easyName($shortName)
{
  if(isset($shortName))
  {
      $first = $shortName[0];
      $second = $shortName[1]+1;
      $out = $first." ";
      if($first == 'A' && !empty($second))
       $out .= ["I","II","III","IV"][$second];
      else
          $out = "<".$out.">";

      return $out;
  }
}

}