<?php
/**
 * Class CriteriaInterface Description
 * Created by  PhpStorm.
 * Created Time 2019-11-27 16:38
 *
 * PHP version 7.1
 *
 * @category CriteriaInterface
 * @package  P:samlc\TpRepository
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\TpRepository;

use think\db\Query;
use think\Model;

interface CriteriaInterface
{
    /**
     * Fun apply Description
     * Created Time 2019-11-27 16:46
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository): Query;
}