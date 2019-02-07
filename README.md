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
In method you use my package like this:
```
StoreGallery::storeGallery('name-of-input', 'location-to-save-pictures/', 'id-news-for-gallery', 'name-of-column-in-tabl-for-gallery', 'width-for-pictures', 'height-for-pictures', true);

width-for-pictures => if you want to scale pictures on any width
height-for-pictures => if you want to scale pictures on any height
if you do not enter the height and width of the pictures, they will not change the size


last parameter mean if you edit exists gallery, and old gallery will be delete
```
