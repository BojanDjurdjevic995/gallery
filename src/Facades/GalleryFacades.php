<?php
namespace Baki\Gallery\Facades;

use File;
use Image;
use App\Gallery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input; 
use Illuminate\Support\Facades\Facade;
/**
 * @see \Mews\Purifier
 */
class GalleryFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'StoreGallery';
    }
    public static function store($picFromInput, $path, $idNews, $id_news='id_news', $width = false, $height = false)
    {
        if (Input::has($picFromInput)) 
        {
            $gallery = Input::file($picFromInput);
            $insert = array();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            for ($i=0; $i < count($gallery); $i++) 
            { 
                $image = $gallery[$i];
                if ( in_array(File::extension($image->getClientOriginalName()), $allowedExtensions) ) 
                {
                    $file_name = time() . "-" . Str::random(5) . '-' . $image->getClientOriginalName();
                    $location  = public_path($path . '' . $file_name);
                    ($width && $height) ? (Image::make($image)->fit($width, $height)->save($location)) : (Image::make($image)->save($location));
                    $insert[] = [$id_news => $idNews, 'image' => $path . '' . $file_name];
                }
            } 
            Gallery::insert($insert);
        }
        return true;
    }
    public static function update($picFromInput, $path, $idNews, $id_news='id_news', $width = false, $height = false)
    {
        if (Input::has($picFromInput)) 
        {
            $gallery = Input::file($picFromInput);
            $insert = array();
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            for ($i=0; $i < count($gallery); $i++) 
            { 
                $image = $gallery[$i];
                if ( in_array(File::extension($image->getClientOriginalName()), $allowedExtensions) ) 
                {
                    $file_name = time() . "-" . Str::random(5) . '-' . $image->getClientOriginalName();
                    $location  = public_path($path . '' . $file_name);
                    ($width && $height) ? (Image::make($image)->fit($width, $height)->save($location)) : (Image::make($image)->save($location));
                    $insert[] = [$id_news => $idNews, 'image' => $path . '' . $file_name];
                }
            }
            $oldGallery = Gallery::where($id_news, '=', $idNews)->get();
            if (!empty($insert)) 
            {   
                $idPic=array();
                foreach($oldGallery as $item)
                {
                    if(File::exists($item->image))
                    {
                        $idPic[] = $item->id;
                        File::delete($item->image);
                    }
                }
                Gallery::insert($insert);
                Gallery::destroy($idPic);
            } 
        }
        return true;
    }
    public static function delete($id, $columnName)
    {
        $img = Gallery::where($columnName, '=', $id)->get();
        $idPic=array();
        foreach($img as $item)
        {
            if(File::exists($item->image))
            {
                File::delete($item->image);
                $item->delete();    
            }
        } 
        return true;
    }
}
