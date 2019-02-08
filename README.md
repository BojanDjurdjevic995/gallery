# Instruction
For this package you must install Image package
```
composer require intervention/image
```
Than you must make model with name "Gallery"

Link this
```
php artisan make:model Gallery
```
Than install my package with this command
```
composer require baki/gallery
```
In Controller you must include this package
```
use StoreGallery;
```
In method you use my package for store gallery like this:
```
StoreGallery::store('name-of-input', 'location-to-save-pictures/', 'id-news-for-gallery', 'name-of-column-in-gallery-table', 'width-for-pictures', 'height-for-pictures');

width-for-pictures => if you want to scale pictures on any width
height-for-pictures => if you want to scale pictures on any height
if you do not enter the height and width of the pictures, they will not change the size
```
Example for store gallery:
```
public function store(Request $r)
{
    $news = new News;
    $news->title = $r->title;
    $news->content = $r->content;
    $news->save();
    StoreGallery::store('gallery', 'gallery/', $news->id, 'news_id', 800, 600);
    return redirect()->route('news.index');
}
```
```
If you have added the height and width to the gallery, update the gallery if you do not just put it this way
StoreGallery::update('gallery', 'gallery/', 25, 'news_id');
```
Example for update gallery:
```
public function update(Request $r, News $news)
{
    $news->title = $r->title;
    $news->content = $r->content;
    $news->save();
    StoreGallery::update('gallery', 'gallery/', $news->id, 'news_id'); //Old gallery will be deleted
    return redirect()->route('news.index');
}
```
