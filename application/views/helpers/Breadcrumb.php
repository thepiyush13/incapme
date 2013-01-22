<?php
/**
 * Description of BreadCrumb
 *
 * @author abhishek
 */
class Zend_View_Helper_Breadcrumb extends Zend_View_Helper_Abstract {
    public function breadcrumb() {
       $front = Zend_Controller_Front::getInstance();
       $request = $front->getRequest();
       $controllerName = $request->getControllerName();
       $actionName = $request->getActionName();
       $title = '';
       $actionTitle = '';
       $url = '';
       
       $crumbs = array(
           'merchants'  => array(
               'title' => 'Index', 
               'actions'=>array( 
                    'add'    => 'Add',
                    'index'  =>  'Manage Merchants',
                   )
               ),
           
           'deals'  => array(
               'title' => 'Deals', 
               'actions'=>array( 
                    'add'    => 'Add',
                    'index'  => 'Manage Deals',
                   'mydeals' => 'My Deals'
                   )
               ),
         );
        if(isset($crumbs[$controllerName]['title']) && $crumbs[$controllerName]['title']) {
            $title = '<a href="/'.$controllerName.'/" class="tip-bottom" data-original-title="">'.$crumbs[$controllerName]['title'].'</a>';
            $actionTitle = '<a href="#" class="current">'.$crumbs[$controllerName]['actions'][$actionName].'</a>';
        }
        $breadCrumb = <<<BOD
        <div id="breadcrumb">
            <a href="/" class="tip-bottom" data-original-title="Go to Home"><i class="icon-home"></i> Home</a>
            $title
            $actionTitle
        </div>
BOD;
       return $breadCrumb;
       }
}

?>
