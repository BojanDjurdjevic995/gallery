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
In your table you must have column 'image' and column for your id of news or post.
Like this:
```
public function up()
{
    Schema::create('galleries', function (Blueprint $table) {
        $table->increments('id');
        $table->unsignedInteger('id_news'); // In my case this ti foreign key from table news. This is ID of my news
        $table->string('image')->nullable();
        $table->timestamps();
    });
}
```
In method you use my package for store gallery like this:
```
StoreGallery::store('App\YourModel', 'input_name', 'yourDisk', 'id_of_your_post_or_news', 'column_in_database');
```
# Example for store gallery:
```
public function store(Request $r)
{
    $news               = new News;
    $news->title        = strip_tags($r->title);
    $news->content      = strip_tags($r->content);
    $news->save();
    StoreGallery::store('App\Gallery', 'gallery', 'galleryNews',  $input->id, 'id_news');
    return redirect()->route('news.index');
}
```
# Example for update gallery:
```
public function update(Request $r, News $news)
{
    $news->title = $r->title;
    $news->content = $r->content;
    $news->save();
    StoreGallery::store('App\Gallery', 'gallery', 'galleryNews',  $news->id, 'id_news', true); // Old gallery will be deleted
    return redirect()->route('news.index');
}
```
For delete gallery use this method:
```
StoreGallery::delete('App\YourModel', $id_news_or_post, 'column_in_database', 'yourDisk');
```
# Example for delete gallery and news
```
public function destroy(News $news)
{
    StoreGallery::delete('App\Gallery', $news->id, 'id_news', 'galleryNews');
    $news->delete();

    return redirect()->route('news.index');
}
```
