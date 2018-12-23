<?php
namespace app\models\interfaces;

interface iDiscount
{
    public function getCategory();

    public function getPreview();

    public function getRegularPrice();

    public function getSpecialPrice();

    public function getDiscountPercent();
}