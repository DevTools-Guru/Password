# Usage

#### Defining your cost

The cost used to hash passwords is important to help slow down brute forcing passwords in case they are lost.
A static method is provided for calculating a good cost time for your servers hardware.
It can be stored in an app-level constant for re-use in your application wherever needed.

<details>
<summary>app/constants.php</summary>

```php
<?php

namespace App;

/** 
 * The cost to hash passwords as returned from:
 * \DevToolsGuru\Password::getAppropriateCostValue()
 * 
 * The projects current target hash time is 0.07 seconds.
 */
const PASSWORD_HASH_COST = 11;

```

</details>

<details>
<summary>app/Services/CreateUser.php</summary>

```php
<?php

namespace App\Services;

use \DevToolsGuru\Password;

class CreateUser 
{
    public function __invoke(array $input)
    {
        // make a new user object
        $password = new Password($input['password'], \App\PASSWORD_HASH_COST);
        // save
    }
}
```

</details>

#### Updating a hash

The default hash algorithm may change or your cost may need to increase or decrease with new server hardware.
To handle these flows, you'll want to check for rehash requirements when you have the password in plaintext.
For example, during a login procedure.

<details>
<summary>app/Services/Login.php</summary>

```php
<?php

namespace App\Service;

use DevToolsGuru\Password;

class Login
{
    public function __invoke(array $input)
    {
        // Get the user from your data store.
        $user = new stdClass();
        $valid = $user->password->verify($input['password']);
        if (!$valid) {
            // Do whatever to not log the user in and let them know it failed.
        }
        //Log the user in
        if ($user->password->needsRehash(\App\PASSWORD_HASH_COST)) {
            $user->password = new Password($input['password'], \App\PASSWORD_HASH_COST);
            $user->save();
        }
    }
}
```

</details>
