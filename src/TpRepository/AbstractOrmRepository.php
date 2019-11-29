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
use think\Model;
use think\Paginator;
use think\Validate;

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
     * AbstractOrmRepository constructor.
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeModel();
        $this->criteria = new Collection();
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
     * @return object|null
     * @throws ValidateException
     */
    public function makeValidator()
    {
        $validatorInstance = null;
        if ($this->validator() != null) {
            $validatorInstance = app()->make($this->validator());
            if (!$validatorInstance instanceof Validate) {
                throw new ValidateException('Class ' . $this->validator() . 'is not instanceof think//Validate');
            }
        }
        return $validatorInstance;
    }

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
    public function save($data = [], Model $model = null): Model
    {
        $validator = $this->makeValidator();
        if ($validator != null) {
            if (!$validator->check($data)) {
                throw new ValidateException($validator->getError());
            }
        }

        if ($model != null) {
            $model->save($data);
            return $model;
        } else {
            $this->model->save($data);
            return $this->model;
        }
    }

    /**
     * Fun findId 根据ID查询
     * Created Time 2019-11-26 17:30
     * Author lichao <lichao@xiaozhu.com>
     *
     * @param Model
     *
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findId($id): Model
    {
        $model = $this->model->get($id);
        if ($model == null) {
            throw new ModelNotFoundException('not found  ID = ' . $id . ' record in table' . $this->model->getTable());
        }
        return $model;
    }

    /**
     * Fun find 查询-单个
     * Created Time 2019-11-29 14:22
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Model
     */
    public function find()
    {
        $this->applyCriteria();
        return $this->model->find();
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
        return $this->model->paginate($limit);
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
        $this->model = $this->model->order($filed, $direction);
    }

    /**
     * Fun findBy 批量查询
     * Created Time 2019-11-29 14:20
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return Collection
     */
    public function findBy(): Collection
    {
        $this->applyCriteria();
        return $this->model->select();
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
        /**
         * @var $criterion CriteriaInterface
         */
        foreach ($this->criteria as $criterion) {
            $this->model = $criterion->apply($this->model, $this);
        }
    }


    /**
     * Fun makeModel 实例化Model
     * Created Time 2019-11-26 18:09
     * Author lichao <lichao@xiaozhu.com>
     *
     *
     * @return object|Model
     * @throws RepositoryException
     */
    protected function makeModel()
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new RepositoryException('Class ' . $this->model() . ' must be an instance of think\\Model');
        }
        return $this->model = $model;
    }

}