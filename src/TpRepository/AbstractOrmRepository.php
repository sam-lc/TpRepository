<?php
/**
 * Class AbstractRepository Description
 * Created by  PhpStorm.
 * Created Time 2019-11-26 17:21
 *
 * PHP version 7.1
 *
 * @category AbstractRepository
 * @author   lichao <lichao@xiaozhu.com>
 * @license  https://lanzu.xiaozhu.com Apache2 Licence
 * @link     https://lanzu.xiaozhu.com
 */

namespace samlc\TpRepository;

use samlc\TpRepository\Exception\ModelNotFoundException;
use samlc\TpRepository\Exception\RepositoryException;
use samlc\TpRepository\Exception\ValidateException;
use think\Collection;
use think\db\Query;
use think\Model;
use think\Paginator;

abstract class AbstractOrmRepository implements RepositoryInterface
{
    /**
     * @var \think\Model $model
     */
    protected $model;

    /**
     * @var Collection
     */
    protected $criteria;

    /**
     * @var Query
     */
    protected $query;

    /**
     * AbstractOrmRepository constructor.
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeModel();
        $this->criteria = new Collection();
        $this->makeQuery();
    }

    /**
     * Fun getModel 获取model类型
     * Created Time 2019-11-26 17:38
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return mixed
     */
    abstract protected function model();

    /**
     * Fun validator 获取验证类
     * Created Time 2019-11-27 11:16
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return mixed
     */
    abstract protected function validator();

    /**
     * Fun makeValidator 实例化校验类
     * Created Time 2019-11-27 11:20
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Validate
     * @throws ValidateException
     */
    public function makeValidator(): Validate
    {
        $validatorInstance = null;
        if ($this->validator() != null) {
            $validatorInstance = app()->make($this->validator());
            if (!$validatorInstance instanceof Validate) {
                throw new ValidateException('Class ' . $this->validator() . 'is not instanceof samlc//TpRepository//Validate');
            }
        }
        return $validatorInstance;
    }

    /**
     * Fun save 存储
     * Created Time 2019-12-09 10:16
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model $model
     *
     * @return Model
     */
    public function save(Model $model): Model
    {
        $model->save();
        return $model;
    }

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
    public function delete(Model $model): Model
    {
        $model->delete();
        return $model;
    }

    /**
     * Fun batchDelete 批量删除
     * Created Time 2019-12-09 11:01
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $ids
     *
     * @return bool
     */
    public function batchDelete(array $ids): bool
    {
        return $this->model->destroy($ids);
    }

    /**
     * Fun assign 赋值操作
     * Created Time 2019-12-11 11:55
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $params
     * @param string $scene
     * @param Model|null $model
     *
     * @return Model
     * @throws RepositoryException
     * @throws ValidateException
     */
    public function assign(array $params, string $scene = '', Model $model = null): Model
    {
        if ($model == null) {
            $this->makeModel(true);
            $model = $this->model;
        }
        $params = array_merge($model->toArray(), $params);
        $params = $this->check($params, $scene);
        foreach ($params as $key => $param) {
            $model->{$key} = $param;
        }
        return $model;
    }

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
    public function saveAll(array $models)
    {
        return $this->model->saveAll($models);
    }

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
    public function check(array $data = [], $scene = ''): array
    {
        $validator = $this->makeValidator();
        if ($scene !== '' && $validator != null) {
            if ($validator->hasScene($scene)) {
                $validator->scene($scene);
            }
            if (!$validator->check($data)) {
                $message = $validator->getMessage();
                throw new ValidateException(
                    str_replace(
                        array_keys($message),
                        array_values($message),
                        $validator->getError()
                    )
                );
            }
        }
        return $this->only($data, $validator->getValidateFields());
    }

    /**
     * Fun only 获取数据指定字段
     * Created Time 2019-12-04 17:28
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $attrs
     * @param array $fields
     *
     * @return array
     */
    protected function only(array $attrs, array $fields)
    {
        $onlyAttr = [];
        if ($fields != null) {
            foreach ($fields as $field) {
                if (isset($attrs[$field])) {
                    $onlyAttr[$field] = $attrs[$field];
                }
            }
        } else {
            $onlyAttr = $attrs;
        }
        return $onlyAttr;
    }

    /**
     * Fun update 批量更新-未进行数据格式正确性验证
     * Created Time 2019-11-29 18:29
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $params 更新数据
     *
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function update(array $params)
    {
        $this->applyCriteria();
        $updateRes = $this->query->update($params);
        $this->resetQuery();
        return $updateRes;
    }

    /**
     * Fun findId 根据id查询,抛出异常
     * Created Time 2019-12-09 16:11
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param $id
     *
     * @return Model
     * @throws ModelNotFoundException
     * @throws \think\Exception\DbException
     */
    public function findId($id): Model
    {
        $this->applyCriteria();
        $model = $this->query->get($id);
        if ($model == null) {
            throw new ModelNotFoundException('not found  ID = ' . $id . ' record in table' . $this->model->getTable());
        }
        $this->resetQuery();
        return $model;
    }

    /**
     * Fun find 单条记录
     * Created Time 2019-11-29 18:33
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function find()
    {
        $this->applyCriteria();
        $model = $this->query->find();
        $this->resetQuery();
        return $model;
    }

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
    public function paginate(int $limit): Paginator
    {
        $this->applyCriteria();
        $paginator = $this->query->paginate($limit);
        $this->resetQuery();
        return $paginator;
    }

    /**
     * Fun limit 数量限制
     * Created Time 2019-12-12 15:33
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param int $offset
     * @param null $length
     *
     */
    public function limit(int $offset, $length = null)
    {
        $this->query = $this->query->limit($offset, $length);
    }

    /**
     * Fun count 总数
     * Created Time 2019-12-12 15:31
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param string $field
     *
     * @return float|string
     */
    public function count($field = '*')
    {
        $this->applyCriteria();
        $count = $this->query->count($field);
        $this->resetQuery();
        return $count;
    }

    /**
     * Fun fields Description
     * Created Time 2019-12-09 15:49
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param array $fields
     * @param bool $except
     */
    public function fields(array $fields = [], bool $except = false): void
    {
        $fields      = $fields != null ? implode(',', $fields) : '*';
        $this->query = $this->query->field($fields, $except);
    }

    /**
     * Fun orderBy 排序
     * Created Time 2019-11-29 14:42
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param string $filed
     * @param string $direction
     *
     */
    public function orderBy(string $filed, string $direction = 'asc'): void
    {
        $this->query = $this->query->order($filed, $direction);
    }

    /**
     * Fun findBy 查找记录
     * Created Time 2019-11-29 18:34
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function findBy(): Collection
    {
        $this->applyCriteria();
        $collection = $this->query->select();
        $this->resetQuery();
        return $collection;
    }

    /**
     * Fun pushCriteria 设置查询标准
     * Created Time 2019-11-27 16:44
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param CriteriaInterface $criteria
     */
    public function pushCriteria(CriteriaInterface $criteria): void
    {
        $this->criteria->push($criteria);
    }

    /**
     * Fun applyCriteria 应用查询标准
     * Created Time 2019-11-27 16:43
     * Author lichao <lichao@xiaozhu.com>
     */
    protected function applyCriteria()
    {
        if (!$this->criteria->isEmpty()) {
            /**
             * @var $criterion CriteriaInterface
             */
            foreach ($this->criteria as $criterion) {
                $this->query = $criterion->apply($this->query, $this);
            }
        }
    }

    /**
     * Fun makeQuery 创建查询器
     * Created Time 2019-11-29 18:25
     * Author lichao <lichao@xiaozhu.com>
     */
    protected function makeQuery(): void
    {
        $this->query = $this->model->newQuery();
    }

    /**
     * Fun resetCriteria 重置查询条件
     * Created Time 2019-12-12 17:18
     * Author lichao <lichao@xiaozhu.com>
     */
    protected function resetQuery()
    {
        $this->criteria = new Collection();
        $this->query    = $this->model->newQuery();
    }

    /**
     * Fun makeModel 实例化Model
     * Created Time 2019-12-11 13:52
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param bool $newInstance
     *
     * @throws RepositoryException
     */
    protected function makeModel(bool $newInstance = false): void
    {
        $model = app()->make($this->model(), [], $newInstance);
        if (!$model instanceof Model) {
            throw new RepositoryException('Class ' . $this->model() . ' must be an instance of think\\Model');
        }
        $this->model = $model;
    }
}
