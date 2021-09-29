<?php

class Form
{
  private $inputs;

  public function __set($name, $value)
  {
    return;
  }

  public function __construct($array)
  {
    $this->inputs = $array;
  }

  public function has($key)
  {
    return array_key_exists($key, $this->inputs);
  }

  public function get($key)
  {
    if ($this->has($key))
    {
      return $this->inputs[$key];
    }

    return null;
  }
}
