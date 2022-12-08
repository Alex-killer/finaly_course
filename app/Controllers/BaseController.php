<?php

namespace App\Controllers;

use App\Controllers\QueryBuilder;
use Aura\SqlQuery\QueryFactory;
use Delight\Auth\Auth;
use League\Plates\Engine;

class BaseController
{
    private $templates;
    private $auth;
    private $qb;
    private $queryFactory;

    public function __construct(Engine $engine, Auth $auth, QueryBuilder $qb)
    {
        $this->templates = $engine;
        $this->auth = $auth;
        $this->qb = $qb;
        $this->queryFactory = new QueryFactory('mysql');
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function getQb()
    {
        return $this->qb;
    }

    public function getQf()
    {
        return $this->queryFactory;
    }
}