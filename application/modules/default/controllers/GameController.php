<?php

class GameController extends Sierra_Controller_Action
{
    public function indexAction()
    {

    }

    public function gameAction()
    {
        $game = new Model_Game(array(
            'slug' => $this->getParam('slug')
        ));
        $game->load();
        $this->view->game = $game;
    }
}