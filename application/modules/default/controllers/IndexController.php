<?php

class IndexController extends Sierra_Controller_Action
{

    public function indexAction()
    {
        $storeItemsModel = new Model_StoreItems();
        $this->view->storeItems = $storeItemsModel->getDisplayItems();

        $gamesModel = new Model_Games();
        $this->view->games  = $gamesModel->getGames(-1, 0, 10);

        $newsModel = new Model_Newss();
        $this->view->newss = $newsModel->getNews(-2, 0, 3);
    }

    public function sierraMagazinesAction()
    {
    }

    public function catalogsAction()
    {
    }

    public function buyersGuidesAction()
    {
    }

    public function tsnTimesAction()
    {
    }

    public function gameSearchAction()
    {
    }

//the following function should become a slug creator for all games in the datatable 'games'. Using the game lighthouse as an example
    public function lighthouseAction()
    {
    }

    public function demosAction()
    {
    }

    public function trailersAction()
    {
    }

    public function booksAction()
    {
    }

    public function merchandiseAction()
    {
    }

    public function aboutUsAction()
    {
    }

    public function linksAction()
    {
    }

}
