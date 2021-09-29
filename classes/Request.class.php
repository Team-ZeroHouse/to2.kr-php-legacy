<?php

class Request
{
  private static $instance = null;

  public $ip;
  public $country = 'ZZ';
  public $path = '/';
  public $method;
  public $msg;
  public $query;

  public function __set($name, $value)
  {
    return;
  }

  public static function get_instance()
  {
    if (self::$instance == null)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function __construct()
  {
    $this->ip = Helper::get_real_ip();
    $this->country();

    if (strpos($_SERVER['REQUEST_URI'], '?a') !== false)
    {
      list($_SERVER['REQUEST_URI'], $_SERVER['ADDI_QUERY']) = explode('?', $_SERVER['REQUEST_URI']);
    }

    if (isset($_SERVER['REDIRECT_URL']))
    {
      $this->path = $_SERVER['REDIRECT_URL'];
    }
    else
    {
      $this->path = $_SERVER['REQUEST_URI'];
    }
    $this->method = $_SERVER['REQUEST_METHOD'];
    $this->msg = new Form($_POST);
    $this->query = new Form($_GET);
  }

  private function country()
  {
    $db = DB::get_instance();
    $ip = ip2long($this->ip);
    $result = $db->query("SELECT `code` FROM `countries` WHERE `start`<={$ip} AND {$ip}<=`end`");
    if ($result->num_rows > 0)
    {
      $this->country = $result->fetch_row()[0];
    }
  }
}
