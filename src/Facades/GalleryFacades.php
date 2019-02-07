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
    public static function storeGallery($picFromInput, $path, $idNews, $id_news='id_news', $w = false, $h = false, $edit = false)
    {
        if (Input::has($picFromInput)) 
        {
            $gallery = Input::file($picFromInput);
            $insert = array();
            $dozvoljeneEkstenzije = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            for ($i=0; $i < count($gallery); $i++) 
            { 
                $image = $gallery[$i];
                if ( in_array(File::extension($image->getClientOriginalName()), $dozvoljeneEkstenzije) ) 
                {
                    $file_name = time()."-".Str::random(5).'-'.$image->getClientOriginalName();
                    $location  = public_path($path . $file_name);
                    ($w && $h) ? (Image::make($image)->fit($w, $h)->save($location)) : (Image::make($image)->save($location));
                    $insert[] = [$id_news => $idNews, 'image' => $path.$file_name];
                }
            }
            if($edit)
            {
                $staragalerija = Gallery::where($id_news, '=', $idNews)->get();
                if (!empty($insert)) 
                {   
                    $idslike=array();
                    foreach($staragalerija as $slika)
                    {
                        if(File::exists($slika->image))
                        {
                            $idslike[] = $slika->id;
                            File::delete($slika->image);
                        }
                    }
                    Gallery::insert($insert);
                    Gallery::destroy($idslike);
                }
            }else{
                Gallery::insert($insert);
            }   
        }
        return true;

    }
}
