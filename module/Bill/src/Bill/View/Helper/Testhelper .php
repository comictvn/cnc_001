<?php

namespace Bill\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Testhelper extends AbstractHelper
{
    
   /* public function __invoke()
   {
      $xmtl = "Welcom Zendvn";
      return htmlspecialchars($xmtl);
   } */
   //nêu truyền tham số sẽ như thế này
   public function __invoke($name=null,$val=null)
   {
      $xmtl = $name.", Welcom Zendvn.";
      return htmlspecialchars($xmtl);
   }
}