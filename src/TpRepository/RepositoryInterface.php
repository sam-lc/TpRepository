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
use think\Collection;
use think\Model;
use think\Paginator;

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
    public function findId($id): Model;

    /**
     * Fun pushCriteria 设置查询标准
     * Created Time 2019-11-27 16:44
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param CriteriaInterface $criteria
     */
    public function pushCriteria(CriteriaInterface $criteria): void;

    /**
     * Fun save 存储
     * Created Time 2019-12-02 10:28
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model $model  模型
     * @param array $data   数据
     * @param string $scene 验证场景
     *
     * @return Model
     * @throws ValidateException
     */
    public function save(Model $model, $data = [], $scene = ''): Model;

    /**
     * Fun paginate 分页
     * Created Time 2019-11-29 14:45
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $limit
     *
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function paginate(int $limit): Paginator;

    /**
     * Fun orderBy 排序
     * Created Time 2019-11-29 14:42
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param string $filed
     * @param string $direction
     *
     */
    public function orderBy(string $filed, string $direction = 'asc'): void;

    /**
     * Fun findBy 批量查询
     * Created Time 2019-11-29 14:20
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Collection
     */
    public function findBy(): Collection;

    /**
     * Fun find 查询-单个
     * Created Time 2019-11-29 14:22
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Model
     */
    public function find();
}
