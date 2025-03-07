<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Ad;
use Illuminate\Support\Facades\DB;

class AdRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Ad::with(['category', 'region', 'saleOption'])->withMax('bids', 'amount')->paginate(10);
    }

    public function getById($id): Ad
    {
        return Ad::with(['user','category','region','saleOption','bids','images','comments'])->withMax('bids','amount')->findOrFail($id);
    }

    public function store(array $data): Ad
    {
        return Ad::create($data);
    }

    public function update(array $data, $id): Ad
    {
        $Ad = Ad::findOrFail($id);
        $Ad->update($data);
        return $Ad;
    }
    public function delete($id): bool
    {
        return DB::transaction(function () use ($id) {
            $Ad = Ad::with(['bids', 'images', 'comments', 'views', 'likes', 'favoritedBy','conversations'])->findOrFail($id);
            foreach ($Ad->images as $image) {
                if (\File::exists($image->image_url)) {
                    \File::delete($image->image_url); 
                }
            }
            if(\File::exists($Ad->primary_image)){
                \File::delete($Ad->primary_image); 
            }
            $Ad->bids()->delete();
            $Ad->comments()->delete();
            $Ad->views()->delete();
            $Ad->likes()->delete();
            $Ad->images()->delete();
            $Ad->conversations()->delete();
            $Ad->favoritedBy()->detach();
            return $Ad->delete();
        });
    }
    
}
