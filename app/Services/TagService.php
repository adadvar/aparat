<?php
namespace App\Services;

use App\Http\Requests\Tag\CreateTagRequest;
use App\Http\Requests\Tag\ListTagsRequest;
use App\Models\Tag;

class TagService extends BaseService {

    public static function index(ListTagsRequest $request){
        return Tag::all();
    }

    public static function create(CreateTagRequest $request){
        $data = $request->validated();
        return Tag::create($data);
    }
}