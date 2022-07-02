<?php
namespace App\Services;

use App\Http\Requests\Category\CreateCategoryRequest;
use App\Http\Requests\Category\ListCategoryRequest;
use App\Http\Requests\Category\UploadCategoryBannerRequest;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService extends BaseService {
    
    public static function getAllCategories(ListCategoryRequest $request){
        $categories = Category::all();
        return $categories;
    }

    
    public static function getMyCategories(ListCategoryRequest $request){
        return auth()->user()->categories;
    }
    
    public static function uploadBanner (UploadCategoryBannerRequest $request){
        try {

            $banner = $request->file('banner');
            $fileName = time() . Str::random(10) . '-banner';
            Storage::disk('category')->put('/tmp/' . $fileName, $banner->get());
            
            return response([
                'banner' =>$fileName
             ], 200);
         }catch (Exception $e){
             return response(['message' => 'An error has occurred !'], 500);
         } 
    }

    public static function create(CreateCategoryRequest $request){
        try{
            DB::beginTransaction();
            $data = $request->validated();
            $user = auth()->user();

            if($request->banner){
                $bannerPath = auth()->id() . '/' . $request->banner;
                Storage::disk('category')->move('tmp/' . $request->banner, $bannerPath);
            }
            $category = $user->categories()->create($data);

            return response([$category], 200);

        }catch(Exception $e){
            DB::rollBack();
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

}