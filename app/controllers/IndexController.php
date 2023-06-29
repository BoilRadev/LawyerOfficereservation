<?php
use Phalcon\Paginator\Adapter\Model as Paginator;
class IndexController extends ControllerBase
{

    public function initialize(){

        $this->view->setTemplateBefore("lawyersAd");
    }
    public function indexAction()
    {
        $paginator = new Paginator(
            [
                'data'  => \DelaTask\Lawyers::find(),
                'limit' => 5,
                'page'  => $this->request->getQuery('page', 'int', 1),
            ]
        );

        $this->view->page = $paginator->getPaginate();
    }

}

