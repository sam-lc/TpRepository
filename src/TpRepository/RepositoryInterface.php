<?php
/**
 * Class AbstractRepository Description
 * Created by  PhpStorm.
 * Created Time 2019-11-26 17:19
 *
 * PHP version 7.1
 *
 * @category AbstractRepository
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\TpRepository;

use samlc\TpRepository\Exception\ValidateException;
use think\Model;

interface RepositoryInterface
{
    /**
     * Fun findId 根据ID查询
     * Created Time 2019-11-26 17:20
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param $id
     *
     * @return Model
     */
    public function find($id): Model;

    /**
     * Fun pushCriteria 设置查询标准
     * Created Time 2019-11-27 16:44
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param CriteriaInterface $criteria
     */
    public function pushCriteria(CriteriaInterface $criteria): void;

    /**
     * Fun save Description 存储
     * Created Time 2019-11-27 10:57
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $data
     * @param Model|null $model
     *
     * @return Model
     * @throws ValidateException
     */
    public function save($data = [], Model $model = null): Model;
}