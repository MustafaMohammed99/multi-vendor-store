<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy extends ModelPolicy
{
    use HandlesAuthorization;
}
