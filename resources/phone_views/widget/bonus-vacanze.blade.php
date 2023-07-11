@if($cliente->bonus_vacanze_2020 ===1  && $locale == 'it')
<div class="bonus_container">
    {!! trans('labels.usare_bonus_vacanze') !!}
</div>
@endif