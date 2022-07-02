<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagsRequest;
use App\Services\CategoryService;
use App\Services\TagService;

class tagController extends Controller
{
    public function index(ListTagsRequest $request){
        return TagService::index($request);
    }

    public function create(CreateTagRequest $request){
        return TagService::create($request);
    }
}
