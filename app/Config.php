<?php

namespace App;

class Config
{
    public function __construct()
    {
        return [
            "database" => [
                "database" => "test",
                "username" => "root",
                "password" => "root",
                "connection" => "mysql:host=mysql",
                "charset" => "utf8"
            ]
        ];
    }
}