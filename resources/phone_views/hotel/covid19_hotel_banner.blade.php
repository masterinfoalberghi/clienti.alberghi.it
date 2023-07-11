<style>

  #covid_hotel {
    background-color: #1565C0;
    border: 1px solid #033e82;
    display: flex;
    width: 100%
  }

  #covid_hotel .icona {
    color: #fff;
    padding-top: 5px; 
  }
  
  #covid_hotel #alert-description {
    color: #fff;
    padding: 5px;
    line-height: 1.5em;
    font-size: 14px;
    text-align: justify;

  }

  #covid_hotel #alert-description span#alert {
    padding: 0px 2px;
    background-color: #fff;
    color: #033e82;
    margin-right: 7px;
  }

  #covid_hotel #alert-description a {
    color: #fff;
    text-decoration: underline;
  }

  #covid_hotel #alert-description a:hover {
    color: #1A5C87;
    text-decoration: none;
  }
  
</style>

<div class="row">
<div id="covid_hotel">
<div id="alert-description">
  {{-- <i class="icona icon-info-circled-1"><span style="display:none;">Info</span></i> --}}
  <span id="alert">COVID-19</span>{!!$testo_covid_banner!!}
</div>
</div>
</div>