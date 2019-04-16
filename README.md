# Instruction
For this package you must install Intervention Image package
```
composer require intervention/image
```
Than install my package with this command
```
composer require baki/gallery
```
In Controller you must include this package
```
use StoreGallery;
```
After that, you must create your disk in config/filesystems.php
```
'yourDisk' => [
            'driver' => 'local',
            'root' => storage_path('app/public/your-directory'),
            'url' => env('APP_URL').'/your-directory',
            'visibility' => 'public',
        ],
```
In method you use my package for store gallery like this:
```
StoreGallery::store('App\YourModel', 'input_name', 'storageDisk', 'id_of_your_post_or_news', 'column_in_database');
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
