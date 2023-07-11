# Risorse statiche su S3 in lettura invece di static.info-alberghi.com


ci dovrà essere una env var che mi fa passare da

> File::exists(base_path('static/' . $path))

a

> Storage::disk('s3')->exists($path)




cercando "(base_path('static/"

in 

app/Hotel.php (2)

app/ImmagineGallery.php (3)

/app/Http/Controllers/Admin/ImmaginiGalleryController.php (2)




# upload della gallery da admin

- quando uploado viene salvata una copia dell'immgine originale storage_path('original_images/gallery'); e non la devo mettere in s3 perché non è in static [NON CONVIENE METTERLE ANCHE QUESTE IN s3???]


# sostituzione a mano



__gulp.js__

- riga 130


sostituire

.pipe(replace("../font/", "/vendor/fontello/font/"))

con

.pipe(replace("../font/", "https://static.info-alberghi.com/vendor/fontello/font/"))


in generale devo sotituire

static.info-alberghi.com

con 

https://static.info-alberghi.com


*ATTENZIONE*

 base_path('static/' => /var/www/html/infoalberghiThirdEye/static/

 __NON CI DEVE PIÙ ESSERE__
