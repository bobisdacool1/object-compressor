# PHP Object Compressor

## RU

* Необходимо реализовать механизм позволяющий максимально "плотно" упаковывать набор пользовательских атрибутов для передачи по сети.
* Основной упор на объем трафика и скорость обмена.

**Использование:**

1) Объект, который мы хотим сжать, должен имплементировать интерфейс `ArrayableInterface`
```php
class User implements ArrayableInterface
{
   private int $id;
   private int $age;

   public function toArray(): array
    {
        return [
            'id' => $this->id,
            'age' => $this->age,
        ];
    }
}
```
2) Создаем экземпляр компрессора
```php
$compressor = new Compressor();
```
3) Упаковываем объект
```php
$compressedUser = $compressor->compressObject($user);
```
4) Отправляем объект по своим делам
5) Распаковываем объект
```php
$userFields = $compressor->uncompressObject($compressedUser);
```
6) Профит!

### Возможные опции для Compressor

- `$lossless`  
Отвечает за передачу данных без потерь (boolean будут передаваться как boolean, int как int и так далее)


- `$useAliases`  
Отвечает за использование алиасов. Если у объекта очень много длинных ключей, то можно


- `$shouldCompress`  
Отвечает за сжатие данных с помощью `gzip`

## EN

* The task is to implement a mechanism that allows the most "dense" packing of a set of user attributes for transmission over the network.
* Main focus on traffic volume and exchange rate.

**Usage:**

1) The object we want to compress must implement the `ArrayableInterface` interface
```php
class User implements ArrayableInterface
{
    private int $id;
    private int $age;

    public function toArray(): array
     {
         return [
             'id' => $this->id,
             'age' => $this->age,
         ];
     }
}
```
2) Create an instance of the compressor
```php
$compressor = new Compressor();
```
3) Pack the object
```php
$compressedUser = $compressor->compressObject($user);
```
4) Send the object on our own business
5) Unpack the object
```php
$userFields = $compressor->uncompressObject($compressedUser);
```
6) Profit!

### Possible options for Compressor

- `$lossless`  
Used for lossless data transfer (boolean will be transferred as boolean, int as int and so on)


- `$useAliases`  
Used for the use of aliases. If an object has a lot of long keys, then you can
 

- `$shouldCompress`  
Used for compressing data with `gzip`