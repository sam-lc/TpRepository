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
use think\db\Query;
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
     * Fun assign 赋值操作
     * Created Time 2019-12-09 11:06
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $params
     * @param string $scene
     * @param Model|null $model
     *
     * @return Model
     * @throws ValidateException
     */
    public function assign(array $params, string $scene = '', Model $model = null): Model;

    /**
     * Fun save 存储
     * Created Time 2019-12-09 10:16
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model): Model;

    /**
     * Fun delete 删除
     * Created Time 2019-12-09 10:36
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model $model
     *
     * @return Model
     * @throws \Exception
     */
    public function delete(Model $model): Model;

    /**
     * Fun batchDelete 批量删除
     * Created Time 2019-12-09 11:01
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $ids
     *
     * @return bool
     */
    public function batchDelete(array $ids): bool;

    /**
     * Fun saveAll Description
     * Created Time 2019-12-10 11:18
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $models
     *
     * @return bool|Collection
     * @throws \Exception
     */
    public function saveAll(array $models);

    /**
     * Fun check 检测
     * Created Time 2019-12-09 09:59
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $data
     * @param string $scene
     *
     * @return array
     * @throws ValidateException
     */
    public function check(array $data = [], $scene = ''): array;

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
     * Fun limit 数量限制
     * Created Time 2019-12-12 15:23
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $offset
     * @param null $length
     *
     * @return Query
     */
    public function limit(int $offset, $length = null): Query;

    /**
     * Fun count 总数
     * Created Time 2019-12-12 15:31
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param string $field
     *
     * @return float|string
     */
    public function count($field = '*');

    /**
     * Fun fields 查询范围
     * Created Time 2019-12-09 15:49
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $fields
     * @param bool $except
     */
    public function fields(array $fields = [], bool $except = false): void;

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
