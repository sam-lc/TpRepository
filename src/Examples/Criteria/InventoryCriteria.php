<?php
/**
 * Class InventoryCriteria Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 17:08
 *
 * PHP version 7.1
 *
 * @category InventoryCriteria
 * @package  P:samlc\Examples\Criterias
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Criteria;

use samlc\TpRepository\CriteriaInterface;
use samlc\TpRepository\RepositoryInterface;
use think\db\Query;

class InventoryCriteria implements CriteriaInterface
{
    protected $inventories;
    protected $operation;

    /**
     * InventoryCriteria constructor.
     * @param int $inventories
     * @param string $operation
     */
    public function __construct(
        int $inventories,
        string $operation = '='
    ) {
        $this->inventories = $inventories;
        $this->operation   = $operation;
    }

    public function apply($model, RepositoryInterface $repository): Query
    {
        return $model->where('inventories', $this->operation, $this->operation);
    }
}