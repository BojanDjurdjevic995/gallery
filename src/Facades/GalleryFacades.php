<?php
namespace Baki\Gallery\Facades;

use File;
use Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input; 
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Storage;

class GalleryFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StoreGallery';
    }
    public static function store($class, $input_name, $storage_disk, $id, $column, $edit = false, $width = false, $height = false)
    {
        if (Input::has($input_name)) 
        {
            $gallery = Input::file($input_name);
            $insert = array();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            for ($i=0; $i < count($gallery); $i++) 
            { 
                $image = $gallery[$i];
                if ( in_array(File::extension($image->getClientOriginalName()), $allowedExtensions) ) 
                {
                    $file_name = 'gallery-' . time() . "-" . strtolower(Str::random(5)) . '-' . $image->getClientOriginalName();
                    $makeImage = ($width && $height) ? Image::make($image)->fit($width, $height) : Image::make($image);
                    $path = Storage::disk($storage_disk)->path($file_name);
                    Storage::disk($storage_disk)->put($file_name, $makeImage->save($path));
                    $insert[] = [$column => $id, 'image' => $file_name];
                }
            }
            if($edit)
            {
                $galleryOld = $class::where($column, '=', $id)->get();
                if (!empty($insert)) 
                {   
                    $idImage=array();
                    foreach($galleryOld as $slika)
                    {
                        if(Storage::disk($storage_disk)->exists($slika->image))
                        {
                            $idImage[] = $slika->id;
                            Storage::disk($storage_disk)->delete($slika->image);
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
        $image = $class::where($columnName, '=', $id)->get();
        foreach($image as $item)
        {
            if(Storage::disk($storage_disk)->exists($item->image))
            {
                Storage::disk($storage_disk)->delete($item->image);
            }
        } 
        return true;
    }
}
