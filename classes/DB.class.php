<?php

class DB
{
  private static $db = null;

  private function __contruct()
  {
  }

  public static function get_instance()
  {
    if (self::$db == null)
    {
      self::$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    return self::$db;
  }
}
