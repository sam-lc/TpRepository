<?php
/**
 * Class ProductOrmRepository Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 11:23
 *
 * PHP version 7.1
 *
 * @category ProductOrmRepository
 * @package  P:samlc\Examples\Repository
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Repository;

use samlc\Examples\Model\Product;
use samlc\Examples\Model\ProductRepository;
use samlc\Examples\Validators\ProductValidator;
use samlc\TpRepository\AbstractOrmRepository;

class ProductOrmRepository extends AbstractOrmRepository implements ProductRepository
{
    protected function model()
    {
        return Product::class;
    }

    protected function validator()
    {
        return ProductValidator::class;
    }
}
