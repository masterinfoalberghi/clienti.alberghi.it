Tutte le chiamate, che hanno davanti //, ADESSO sono automaticamente convertite a https://static.info-alberghi.com/...

es: https://static.info-alberghi.com/vendor/jquery/jquery.min.js?5.7.4




PRIMA la chiamata era 

http://static.info-alberghi.com/vendor/jquery/jquery.min.js?5.7.4

siccome nel file /etc/hosts
127.0.1.1 		static.info-alberghi.com

e c'era un virtual host static.info-alberghi.com.conf che punta a /var/www/html/infoalberghiThirdEye/static





DEVO creare un VH static.info-alberghi.ssl.conf (come tutti gli altri VH che rispondono a richieste ssl !!!!) che punta a che punta a /var/www/html/infoalberghiThirdEyeSSL/static
e
DEVO sostituire tutte le chiamate a static.info-alberghi.com con static.info-alberghi.ssl (cioè il SOLO file .env)

e nel file /etc/hosts

siccome nel file /etc/hosts
127.0.1.1 		static.info-alberghi.ssl



Su InfoAlberghi tutte le risorse sono chiamate con Utility::asset(

che utilizza per comporre l'url la variabile APP_CDN definita nel file .env


in questo caso:
APP_CDN=//static.info-alberghi.ssl


**ATTENZIONE**

http://www.danieleckermann.com/blog/force-https-laravel

By default Laravel generates URLs with HTTP instead of HTTPS.

This is great when developing locally on your machine, but on a production server you probably got Let's Encrypt and HTTPS.

Forcing HTTP from AppServiceProvider


<code>

<?php

	namespace App\Providers;

	use Illuminate\Support\ServiceProvider;

	class AppServiceProvider extends ServiceProvider
	{
	    /**
	     * Register any application services.
	     *
	     * @return void
	     */
	    public function register()
	    {
	        if (env('APP_ENV') === 'production') {
	            $this->app['url']->forceScheme('https');
	        }
	    }
	}
	
</code>



1. url canonical in hp con funzione url() [tutti]
2.Utility::getCurrentUri() verifica anche Config::get("app.ssl") perché $_SERVER['HTTPS'] con ngnix davanti ad apache NON si sa cosa restituisca
3. ci sono alcune pagine (quante ?) come questa: /hotel-piscina/cesenatico-hotel-piscina.php che hanno all'interno dei link a pagine interne con http inserite mediate WYSIWYG 
con una query del tipo 
select * from tblCmsPagine where descrizione_1 like'%http://www.info-alberghi.com%' or descrizione_2 like'%http://www.info-alberghi.com%'
le pagine sembrano essere 235

UPDATE tblCmsPagine
SET descrizione_1 = REPLACE (descrizione_1, 'http://www.info-alberghi.com', '//www.info-alberghi.com')
WHERE descrizione_1 like'%http://www.info-alberghi.com%'
Affected rows: 20

UPDATE tblCmsPagine
SET descrizione_2 = REPLACE (descrizione_2, 'http://www.info-alberghi.com', '//www.info-alberghi.com')
WHERE descrizione_2 like'%http://www.info-alberghi.com%'
Affected rows: 215 

4. There is a general trend towards using 'https' more widely, and you can already write 'https://schema.org' in your structured data. 

5. ancora link alternate

6. Paginazione (es: https://alpha.info-alberghi.com/hotel-bambini/riviera-romagnola-hotel-bambini.php)
$users = User::paginate(20);
$users->setPath('');
For this, you'll get links like this
<a href="?page=1">1</a>

7. il file /js-above/listing.js chima un altro js che deve essere su static; quindi è stato inserito il persorco assoluto direttamente nel js
	//static.info-alberghi.com/mobile/js/markerclusterer.min.js

8. Utility::asset() Vs asset() in composer/puntiDiInteresse.blade.php

9. nel file google-maps-scheda-hotel.js è stato inserito il path assoluto a //static.info-alberghi.com/ per l'immagine della mappa mobile

10. footer con url()

11. gli hreflang sono gestiti con un MIDDLEWARE HrefLang per tutte le pagine tranne che per le home che lehanno direttamente nei template. 
Devono essere uniformati tutti in questo modo.

<link rel="alternate" hreflang="x-default" href="PAGINA IT sempre">
<link rel="alternate" hreflang="it" href="PAGINA IT">
<link rel="alternate" hreflang="en" href="PAGINA EN">
<link rel="alternate" hreflang="fr" href="PAGINA FR">
<link rel="alternate" hreflang="de" href="PAGINA DE">

mentre PRIMA la homepage del phone non metteva MAI il default e nonb metteva l'href alternate della lingua corrente



12. In preg_replace_callback() sostituire

create_function( '$matches', 'return "<span translate=\"no\">".$matches[1]."</span>";' ),

con

function ($matches) { return "<span translate=\"no\">".$matches[1]."</span>"; },

13. css footer scheda hotel newsletter

14. pagina statica link js solo per il tablet

15. target blank GDPR form




**METTERE alpha al posto di master (senza perdere lo storico su master/www)**


0. BACKUP di  infoalberghiThirdEyeSSL e infoalberghiThirdEye e master sul SERVER

0.1 CANCELLO la folder .git/ da infoalberghiThirdEyeSSL in modo da NON sovrascrivere lo storico di www quando la copio su infoalberghiThirdEye




0.2 CANCELLO da infoalberghiThirdEyeSSL i file del .gitignore per NON SOVRASCRIVERE la copia locale di www. 

Quindi tolgo questi file (se ci sono) da infoalberghiThirdEyeSSL


.DS_Store
.phpintel
.profile
.lock
.sublime-workspace
.sublime-project
npm-debug.log
php_error.log

#Folder
/ssl
/work
/vendor
/node_modules
/storage/logs/*
/storage/framework/cache/*
/storage/framework/sessions/*
/storage/framework/views/*
/public/greenbooking/img/piantumazioni/originali
/public/images/pagine

#Configurazioni
.env
config/cache.php

# Htaccess
public/.htaccess
public/.htpasswd

# Sitemap
public/images_sitemap.xml
public/images_sitemap1.xml
public/images_sitemap2.xml
public/sitemap.xml
public/sitemapindex.xml
public/sitemap_immagini_cdn.xml

#Old version
old_version
old
WT_OLD

#Static
/static/* TUTTO !!!!


0.3 confrontare il file .env locale con quello online (CI SONO DELLE CONFIGURAZIONI CHE VANNO AGGIUNTE come quelle della cache)

1. copio la infoalberghiThirdEyeSSL dentro infoalberghiThirdEye [IN LOCALE] in modo da cambiare la Working Tree

2. git add --all . | git commit -m "Modifiche x SSL" [IN LOCALE] per cambiare il repository

3. git push per cambiare il remote repository 

4. [SUL SERVER] lancio "composer update" per eventuali allineamenti 

5. lanciare query per cercare http:// nelle pagine 

con una query del tipo 
select * from tblCmsPagine where descrizione_1 like'%http://www.info-alberghi.com%' or descrizione_2 like'%http://www.info-alberghi.com%'
le pagine sembrano essere 235

UPDATE tblCmsPagine
SET descrizione_1 = REPLACE (descrizione_1, 'http://www.info-alberghi.com', '//www.info-alberghi.com')
WHERE descrizione_1 like'%http://www.info-alberghi.com%'
Affected rows: 20

UPDATE tblCmsPagine
SET descrizione_2 = REPLACE (descrizione_2, 'http://www.info-alberghi.com', '//www.info-alberghi.com')
WHERE descrizione_2 like'%http://www.info-alberghi.com%'
Affected rows: 215 













