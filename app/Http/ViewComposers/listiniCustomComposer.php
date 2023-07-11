<?php

/**
 *
 * View composer per render listini variabili:
 * @parameters: cliente, titolo, locale
 *
 *
 *
 */

namespace App\Http\ViewComposers;
use Illuminate\Contracts\View\View;

class listiniCustomComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $viewdata = $view->getData();
        $cliente = $viewdata['cliente'];
        $locale = $viewdata['locale'];
        $titolo = isset($viewdata['titolo']) ? $viewdata['titolo'] : '';

        if (isset($titolo) && $titolo != '') {
            $titolo = strtoupper($titolo);
        } else {
            $titolo = '';
        }

        // le associazioni sono precaricate con nell'eager loading (es: listiniCustom è già com il vincolo attivo())
        $listiniCustom = $cliente->listiniCustom;

        $view->with([
            'listini' => $listiniCustom,
            'titolo' => $titolo,
            'locale' => $locale,
        ]);

    }
}
