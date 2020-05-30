<?php
namespace app\interfaces;

interface iMarket
{
    public const DOWNLOAD_LIMIT = 50;

    public function getData();

    public function updateData();

    public function getPreparedData();

    public function downloadImages();
}