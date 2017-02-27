# API

The Password object provides you with all the information you will need to manage a users password.

## Objects

### DevToolsGuru\Password

```php
new \DevToolsGuru\Password(string $value, int $cost = 10);
```

The primary class in the package.
When creating a new object you **MUST** provide the value. 
You *may* provide the cost to use if the value is not already a hash.

When creating a new hash, the length of the password will be checked 
to make sure it isn't in excess of a known limitation.
If it does, then the `\DevToolsGuru\Password\ExcessiveLengthException` will be thrown.
This can be turned off in your application by doing the following at any point before instantiation:

```php
\DevToolsGuru\Password::$errorOnExcessiveLength = false;
```

#### Methods

##### getHash() : string

Get the stored hash.

```php
$password->getHash();
```

##### getInfo() : \DevToolsGuru\Password\Info

Get the password info object for the hash.

```php
$password->getInfo();
```

##### verify(string $plainText) : bool

Verify the current objects value against the provided plain-text value.

```php
$password->verify('TheProvidedPassword');
```

##### needsRehash(int $cost = 10) : bool

Checks if the currently stored password needs to be rehashed.
The cost desired can be passed in and the current PHP default algorithm is used to compare against.

```php
$password->needsRehash(12);
```

##### static hasMaxLength(int $algorithm) : bool

See if the given algorithm identifier has a known maximum length limit that will be used for hashing.

```php
\DevToolsGuru\Password::hasMaxLength(PASSWORD_DEFAULT);
```

##### static getAppropriateCostValue(float $targetTime = 0.05) : int

Use this method to test your server's hardware to find a good default cost for hashing.

> **Warning** Do **NOT** use this during an applications general lifecycle.
Only check the hardware once and store the output.

```php
\DevToolsGuru\Password::getAppropriateCostValue(0.09); // Checking for 900ms hash time
```

### DevToolsGuru\Password\Info

The Info class provides the data from `password_get_info` in an object form.
The `salt` option is not handled since it is deprecated as of 7.0.

#### Methods

##### getCost() : int

Get the cost used to hash the password.

```php
$info->getCost();
```

##### getAlgorithm() : int

Get the algorithm identifier used to hash the password.

```php
$info->getAlgorithm();
```

##### getAlgorithmName() : string

Get the algorithm's named used to hash the password.
Returns 'unknown' if the hash was not a valid hash.

```php
$info->getAlgorithmName();
```
