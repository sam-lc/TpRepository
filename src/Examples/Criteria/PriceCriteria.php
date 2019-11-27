<?php
/**
 * Class PriceCriteria Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 16:46
 *
 * PHP version 7.1
 *
 * @category PriceCriteria
 * @package  P:samlc\Examples\Criterias
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Criteria;

use samlc\TpRepository\CriteriaInterface;
use samlc\TpRepository\RepositoryInterface;

class PriceCriteria implements CriteriaInterface
{
    protected $price;
    protected $operation;

    /**
     * PriceCriteria constructor.
     * @param float $price
     * @param string $operation
     */
    public function __construct(float $price, string $operation = '=')
    {
        $this->price = $price;
    }

    /**
     * Fun apply Description
     * Created Time 2019-11-27 16:48
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param \think\Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed|void
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('price', $this->operation, $this->price);
    }
}