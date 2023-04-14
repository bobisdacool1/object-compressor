# PHP Object Compressor
## EN

* The task is to implement a mechanism that allows the most "dense" packing of a set of user attributes for transmission over the network.
* Main focus on traffic volume and exchange rate.

The main concept is to generate a protocol that will contain information
about which bit contains an object field's information.
Once the protocol has been established, we can start sending densely packed bits,
which will only represent the values of the object's fields.

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
2) Create a protocol to exchange data
```php
$protocol = new Protocol($user)
```
3) Create an instance of the compressor
```php
$compressor = new Compressor($protocol);
```
4) Pack the object
```php
$compressedUser = $compressor->compress($user);
```
4) Send the object on our own business
5) Unpack the object
```php
$userFields = $compressor->uncompress($compressedUser);
```
6) Profit!