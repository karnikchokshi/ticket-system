<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesPermissions extends Model
{
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    public function role() {
        return $this->belongsTo('App\Models\Roles', 'role_id', 'id');
    }

    public function permission() {
        return $this->belongsTo('App\Models\Permissions', 'permission_id', 'id');
    }
}
