<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use NewsApiDevlop;
use App\Http\Requests;

class NewsController extends Controller
{


    public function index(Request $request)
    {
        $result = [];
        if ($request->secret !== env('SECRET_KEY_USER')) {
            return;
        }
        switch ($param = $request->get("methodGetNews")) {
            case "list":
                $result = NewsApiDevlop::topHeadlines()
                    ->setCountry((string)$request->country ? : '')
                    ->setCategory((string)$request->category ? : '')
                    ->setsources((string)$request->sources ? : '')
                    ->setpageSize((int)$request->pageSize ? : '')
                    ->setpage((int)$request->page ? : '')
                    ->get()
                    ->articles;
                break;
            case "detail":
//                    $request = NewsApiDevlop::
                break;
            default:
                return "Error , please enter param- 'methodGetNews'  - list or detail news";
        }

        var_dump($result);
        die();

    }
}
