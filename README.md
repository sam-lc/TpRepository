#install

composer require samlc/tp-repository
# 为什么会写这个组件
thinkphp中model的职责过重
- 业务逻辑处理
- 数据库交互

eg:  

```php
/**
* 产品类
*/
use think\Model;

class Product extends Model
{
    /**
    * 数据查询职责
    */
    public static function getByType(string $type): self
    {
        return self::where('type','=',$type)->select();
    }
    /**
    * 存储职责
    */
    public static funtion creator(array $product): self
    {
        $productModel = new self($product);
        $productModel->save();
        return $productModel;
    }
    
    /**
    * 是否还在销售
    */
    public function onSale(): bool
    {
        return intval($this->onSale) === 1;
    }
    
    /**
    * 销售减少库存
    */
    public function reduceInventories(int $saleCount)
    {
        $this->inventories -= $saleCount;
        return $this;
    }
}
```
我希望可以将数据库交互部分拆离出来

Product处理业务逻辑
```php
/**
* 产品类
*/
class Product extends Base
{
    /**
    * 是否还在销售
    */
    public function onSale(): bool
    {
        return intval($this->onSale) === 1;
    }
    
    /**
    * 销售减少库存
    */
    public function reduceInventories(int $saleCount)
    {
        $this->inventories -= $saleCount;
        return $this;
    }
}
```
将与数据库交互的职责交于Repository
```php
class ProductRepository implements RepositoryInterface
{
    public function getByType(string $type): Product
} 
```

    