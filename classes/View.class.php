<?php

class View
{
  private $file;
  public $error = true;

  public function __set($name, $value)
  {
    return;
  }

  public function __construct($view_name)
  {
    $file = '../views/' . $view_name;
    if (file_exists($file))
    {
      $this->file = $file;
      $this->error = false;
    }
  }

  public function show()
  {
    if ($this->error)
    {
      exit_with_code(500);
    }
    else
    {
      header('Content-Type: text/html; charset=utf-8');
      include($this->file);
      exit_with_code(200, false);
    }
  }
}
