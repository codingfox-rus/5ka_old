<?php
namespace app\interfaces;

interface iMarket
{
    const DOWNLOAD_LIMIT = 50;

    public function getFilePath();

    public function getData();

    public function updateData();

    public function getPreparedData();

    public function getItem(array $result);

    public function archiveData();

    public function downloadImages();
}