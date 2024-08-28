<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

class Info_product extends Model
{
    public function getList() {

        $products = self::with('company')->get();

        return $products;
    }
}
