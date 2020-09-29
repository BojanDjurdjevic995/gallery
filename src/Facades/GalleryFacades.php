<?php
namespace Baki\Gallery\Facades;

use Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;

class GalleryFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StoreGallery';
    }

    public static function store($class, $input_name, $storage_disk, Request $request, $id, $column, $edit = false, $width = false, $height = false)
    {
        if ($request->has($input_name))
        {
            $gallery = $request->file($input_name);
            $insert  = array();
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            foreach ($gallery as $item)
                if ( in_array($item->getClientOriginalExtension(), $allowedExtensions) )
                {
                    $file_name  = 'gallery-' . time() . "-" . strtolower(Str::random(10)) . '-' . $item->getClientOriginalName();
                    $makeImage  = ($width && $height) ? Image::make($item)->fit($width, $height) : Image::make($item);
                    $path       = Storage::disk($storage_disk)->path($file_name);
                    Storage::disk($storage_disk)->put($file_name, $makeImage->save($path));
                    $insert[]   = [$column => $id, 'image' => $file_name];
                }

            if($edit)
            {
                $galleryOld = $class::where($column, '=', $id)->get();
                if (!empty($insert)) 
                {   
                    $idImage=array();
                    foreach ($galleryOld as $value)
                    {
                        if (Storage::disk($storage_disk)->exists($value->image))
                        {
                            $idImage[] = $value->id;
                            Storage::disk($storage_disk)->delete($value->image);
                        }
                    }
                    $class::insert($insert);
                    $class::destroy($idImage);
                }
            }
            else{
                $class::insert($insert);
            }   
        }
        return true;
    }

    public static function delete($class, $id, $columnName, $storage_disk)
    {
        $images = $class::where($columnName, '=', $id)->get();
        foreach($images as $item)
            if(Storage::disk($storage_disk)->exists($item->image))
                Storage::disk($storage_disk)->delete($item->image);

        return true;
    }
}
