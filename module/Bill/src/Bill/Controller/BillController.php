<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Bill\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class BillController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel(array('a'=>'abc', 'b'=>'123'));
    }
    public function menuAction()
    {
    	return new ViewModel(array('menuphai'=>'Menu phai'));
    }  
    public function listproductAction() 
    {
    	return new ViewModel();
    }
}
