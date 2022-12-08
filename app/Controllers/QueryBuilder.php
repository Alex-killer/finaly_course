<?php

namespace App\Controllers;

use Aura\SqlQuery\QueryFactory;
use JasonGrimes\Paginator;
use PDO;


class QueryBuilder
{
    private $queryFactory;
    private $pdo;
    private $totalItems;
    private $usersPaginate;

    public function __construct(PDO $pdo)
    {
       $this->queryFactory = new QueryFactory('mysql');
       $this->pdo = $pdo;
    }

    public function getAll($table)
    {

        $select = $this->queryFactory->newSelect();

        $select
            ->cols(['*'])
            ->from($table);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $this->totalItems = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $this->totalItems;

    }

    public function paginate($table)
    {
        $select = $this->queryFactory->newSelect();

        $select
            ->cols(['*'])
            ->from($table)
            ->setPaging(3)
            ->page($_GET['page'] ?? 1);
        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $this->usersPaginate = $sth->fetchAll(PDO::FETCH_ASSOC);

        $itemsPerPage = 3;
        $currentPage = $_GET['page'] ?? 1;
        $urlPattern = '?page=(:num)';

        $paginator = new Paginator(count($this->totalItems), $itemsPerPage, $currentPage, $urlPattern);
        return $paginator;
    }

    public function getItemPaginate()
    {
        return $this->usersPaginate;
    }

    public function getOne($id, $table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);


        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }

    public function update($id, $data, $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());
        $sth->execute($update->getBindValues());
    }

    public function getData($id, $table)
    {
        $data = $this->getOne($id, $table);
        foreach ($data as $item) {
            return $item;
        }
    }
}