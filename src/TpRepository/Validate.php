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

    /**
     * Fun getValidateFields 获取验证字段
     * Created Time 2019-12-04 17:24
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return array
     */
    public function getValidateFields(): array
    {
        if ($this->currentScene != null) {
            return $this->only;
        } else {
            return array_keys($this->rule);
        }
    }

}