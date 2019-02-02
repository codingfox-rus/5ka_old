<?php
namespace app\interfaces;

interface iMarket
{
    public function getFilePath();

    public function getData();

    public function getPreparedData();

    public function getItem(array $result);
}