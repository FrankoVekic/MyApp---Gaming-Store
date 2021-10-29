<?php 

class View 
{
    private $template;

    public function __construct($template='template')
    {
        $this->template = $template;
    }

    public function render($website,$parameters=[])
    {
        ob_start();
        extract($parameters);
        include BP_APP . 'view' . DIRECTORY_SEPARATOR . $website . '.phtml';
       
        $content = ob_get_clean();

        include BP_APP . 'view' . DIRECTORY_SEPARATOR . $this->template . '.phtml';
    }
}