<?php

namespace Foolz\FoolFuuka\Plugins\ColorFilter\Model;

use Foolz\FoolFuuka\Model\Comment;

class Filter
{ 
  public static function infilter($result)
  {
    $data = $result->getObject();
    if(!$data->radix->getValue('plugin_colorfilter_enable'))
    {
      return null;
    }
    $data->comment->comment = preg_replace_callback('/K(?:ö|Ö)CS(?:ö|Ö)G/i',
      function($hit)
      {
        $hit[0] .= '~';
        mt_srand();
        for($i = 0; $i < 9; ++$i)
        {
          $hit[0] .= mt_rand(0,9);
        }
        return $hit[0];
      },
     $data->comment->comment);
    return null;
  }
  public static function outfilter($result)
  {
    $data = $result->getObject();
    if(!$data->radix->getValue('plugin_colorfilter_enable'))
    {
      return null;
    }
    else
    {
      $result->set(preg_replace_callback("/(".htmlentities("KÖCSÖG").")~(\d{9})/i",
        function ($hit)
        {
          mt_srand($hit[2]);
          $style = mt_rand(0, 999);
          switch ($style)
          {
            case 0:  //a KÖCSÖG is you
              return '<span class="lottery_winner_animation">'.$hit[1].'</span>';
            case 1:
            case 2: //shavale romale
            case 3:
              return '<span class="zeb" comment="waiting for all major browsers to implement :nth-letter(N)">'.$hit[1].'</span>';
        //insert other KÖCSÖGs here
            default: //plain köcsög
              return '<span style="text-transform: uppercase; font-weight: bold; color: rgb('.mt_rand(0,255).','.mt_rand(0,255).','.mt_rand(0,255).'); background-color: rgb('.mt_rand(0,255).','.mt_rand(0,255).','.mt_rand(0,255).')">'.$hit[1].'</span>';
          }
        }, $result->getParam('comment')));
      return null;
    }
  }
}
