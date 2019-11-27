<?php
/**
 * Class Product Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 11:22
 *
 * PHP version 7.1
 *
 * @category Product
 * @package  P:samlc\Examples\Model
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\Examples\Model;

use think\Model;

class Product extends Model
{
    /**
     * Fun onSale 是否在售
     * Created Time 2019-11-27 11:28
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return bool
     */
    protected function onSale(): bool
    {
        return intval($this->onSale) === 1;
    }

    /**
     * Fun reduceInventories 减少库存
     * Created Time 2019-11-27 11:41
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $saleCount
     *
     * @return $this
     * @throws \Exception
     */
    protected function reduceInventories(int $saleCount)
    {
        if ($this->inventoriesEnough($saleCount)) {
            $this->inventories -= $saleCount;
        } else {
            throw new \Exception('库存不足');
        }
        return $this;
    }

    /**
     * Fun inventoriesEnough 库存是充足
     * Created Time 2019-11-27 11:41
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $saleCount
     *
     * @return bool
     */
    protected function inventoriesEnough(int $saleCount)
    {
        return $this->inventories > $saleCount;
    }

    /**
     * Fun total 计算总价
     * Created Time 2019-11-27 11:30
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $saleCount
     *
     * @return float
     */
    public function total(int $saleCount): float
    {
        return $this->price * $saleCount;
    }

    /**
     * Fun changePrice 变更价格
     * Created Time 2019-11-27 11:34
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param float $price
     *
     * @return Product
     */
    public function changePrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Fun sale 销售
     * Created Time 2019-11-27 11:31
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $saleCount
     *
     * @return Product
     * @throws \Exception
     */
    public function sale(int $saleCount): self
    {
        if ($this->onSale()) {
            $this->reduceInventories($saleCount);
            return $this;
        } else {
            throw new \Exception('商品已经下架啦！');
        }
    }
}