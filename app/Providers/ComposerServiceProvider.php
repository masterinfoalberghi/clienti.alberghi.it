<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
  {

  /**
   * Let's register our view composers within this service provider
   * Now that we have registered the composer, the PuntiDiForzaComposer@compose method will be executed
   * each time the puntiDiForza view is being rendered.
   *
   * @return void
   */
  public function boot()
    {
    view()->composer('composer.puntiDiForza', 'App\Http\ViewComposers\PuntiDiForzaComposer');
    view()->composer('composer.puntiDiInteresse', 'App\Http\ViewComposers\PuntiDiInteresseComposer');
    view()->composer('composer.bambiniGratis', 'App\Http\ViewComposers\BambiniGratisComposer');
    view()->composer('composer.offerteLast', 'App\Http\ViewComposers\OfferteLastComposer');
    view()->composer('composer.offerte', 'App\Http\ViewComposers\OfferteComposer');
    view()->composer('composer.listino', 'App\Http\ViewComposers\ListinoComposer');
    view()->composer('composer.servizi', 'App\Http\ViewComposers\ServiziComposer');
    view()->composer('composer.serviziHotelSimili', 'App\Http\ViewComposers\ServiziHotelSimiliComposer');
    view()->composer('composer.serviziGratuiti', 'App\Http\ViewComposers\ServiziGratuitiComposer');
    view()->composer('composer.aperture', 'App\Http\ViewComposers\ApertureComposer');
    view()->composer('composer.orari', 'App\Http\ViewComposers\OrariComposer');
    view()->composer('composer.hotelGallery', 'App\Http\ViewComposers\HotelGalleryComposer');
    view()->composer('composer.offertaListing', 'App\Http\ViewComposers\OffertaListingComposer');
    view()->composer('composer.bambiniGratisListing', 'App\Http\ViewComposers\BambiniGratisListingComposer');
    view()->composer('composer.listinoMinMax', 'App\Http\ViewComposers\listinoMinMaxComposer');
    view()->composer('composer.listiniCustom', 'App\Http\ViewComposers\listiniCustomComposer');
    view()->composer('composer.coupon', 'App\Http\ViewComposers\CouponComposer');
    view()->composer('composer.footer', 'App\Http\ViewComposers\FooterComposer');
    view()->composer('composer.couponListing', 'App\Http\ViewComposers\CouponListingComposer');
    view()->composer('composer.stelle', 'App\Http\ViewComposers\StelleComposer');
    view()->composer('composer.mailMultiplaSelectLocalita', 'App\Http\ViewComposers\MailMultiplaSelectLocalitaComposer');
    view()->composer('composer.searchMultiplaSelectLocalita', 'App\Http\ViewComposers\SearchMultiplaSelectLocalitaComposer');
    view()->composer('composer.formSelectAdultiBambini', 'App\Http\ViewComposers\FormSelectAdultiBambiniComposer');
    view()->composer('composer.formSelectAdultiBambiniMobile', 'App\Http\ViewComposers\FormSelectAdultiBambiniMobileComposer');
    view()->composer('composer.formDatePicker', 'App\Http\ViewComposers\FormDatePickerComposer');
    view()->composer('composer.vetrina', 'App\Http\ViewComposers\VetrinaComposer');
    view()->composer('composer.offertePrenotaPrima', 'App\Http\ViewComposers\OffertePrenotaPrimaComposer');
    view()->composer('composer.offerteTop', 'App\Http\ViewComposers\OfferteTopComposer');
    view()->composer('composer.offerteBambiniGratisTop', 'App\Http\ViewComposers\offerteBambiniGratisTopComposer');
    view()->composer('composer.infoPiscina', 'App\Http\ViewComposers\InfoPiscinaComposer');
    view()->composer('composer.infoBenessere', 'App\Http\ViewComposers\InfoBenessereComposer');
    view()->composer(['composer.search_first','composer.search_first_short'], 'App\Http\ViewComposers\SearchFirstComposer');

    // Siccome nella view composer.schemaOrgHotel ho bisogno di dati che vengono giàù reovati in altri Composer
    // chiamo questi composer prima di renderizzare la view composer.schemaOrgHotel in modo da avere i dati che mi servono 
    view()->composer('composer.schemaOrgHotel', 'App\Http\ViewComposers\SchemaOrgHotelComposer');
    view()->composer('composer.schemaOrgHotel', 'App\Http\ViewComposers\ServiziComposer');
    view()->composer('composer.serviziCovid', 'App\Http\ViewComposers\ServiziCovidComposer');


    
    
    }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
    {
    }
  }
