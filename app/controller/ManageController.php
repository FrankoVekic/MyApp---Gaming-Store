<?php 

class ManageController extends AdminController
{
    private $viewDir = 'manage' . DIRECTORY_SEPARATOR;

    public function games ()
    {
        $this->view->render($this->viewDir . 'games');
    }

    public function equipment()
    {
        $this->view->render($this->viewDir . 'equipment');
    }

    public function services ()
    {
        $this->view->render($this->viewDir . 'services');
    }
}