<?php
/**
 * Class ProductList Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 17:11
 *
 * PHP version 7.1
 *
 * @category ProductList
 * @package  P:samlc\Examples\Services\Product
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Services\Product;

use samlc\Examples\Criteria\InventoryCriteria;
use samlc\Examples\Criteria\PriceCriteria;
use samlc\Examples\Model\ProductRepository;

class ProductList
{
    protected $productRepository;

    /**
     * ProductList constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(float $price, int $inventories)
    {
        $this->productRepository->pushCriteria(new PriceCriteria($price, '>'));
        $this->productRepository->pushCriteria(new InventoryCriteria($inventories, '>'));
        return $this->productRepository->select();
    }
}
