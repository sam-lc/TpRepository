<?php
/**
 * Class Validate Description
 * Created by  PhpStorm.
 * Created Time 2019-12-02 17:36
 *
 * PHP version 7.1
 *
 * @category Validate
 * @package  P:samlc\TpRepository
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\TpRepository;

abstract class Validate extends \think\Validate
{
    /**
     * @return array
     */
    abstract public function getMessage(): array;
}