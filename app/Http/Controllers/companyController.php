<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class companyController extends Controller
{
    public function product()
    {
        //belongsToメソッドを使用して、あるモデルが他のモデルに属する関係を定義//
        return $this->belongsTo('App\Product');
    }
}
