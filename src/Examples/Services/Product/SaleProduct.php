<?php
/**
 * Class SaleProduct Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 11:35
 *
 * PHP version 7.1
 *
 * @category SaleProduct
 * @package  P:samlc\Examples\Services\Product
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Services\Product;

use samlc\Examples\Model\Product;
use samlc\Examples\Model\ProductRepository;

class SaleProduct
{
    protected $productRepository;

    /**
     * SaleProduct constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Fun execute 销售产品
     * Created Time 2019-11-27 11:42
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $productId
     * @param int $saleCount
     *
     * @return float
     * @throws \samlc\TpRepository\Exception\ValidateException
     */
    public function execute(int $productId, int $saleCount): float
    {
        /**
         * @var Product $product
         */
        $product = $this->productRepository->findId($productId);
        $product->sale($saleCount);
        $total = $product->total($saleCount);
        $this->productRepository->save($product);
        return $total;
    }
}
