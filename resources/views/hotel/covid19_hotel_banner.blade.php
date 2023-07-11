<style>

  #covid_hotel {
    background-color: #1565C0;
    border: 1px solid #033e82;
    margin: 0px 0 30px 0;
    display: flex;
    width: 100%
  }
  
  #covid_hotel #alert-description {
    font-size: 14px; 
    padding: 2px 5px; 
    color: #fff;
    text-align: justify;
  }

  #covid_hotel #alert-description span#alert {
    font-size: 14px;
    padding: 1px 2px;
    background-color: #fff;
    color: #033e82;
    margin-right: 10px;
  }

  #covid_hotel #alert-description a {
    color: #fff;
    text-decoration: underline;
    font-weight: 500;
  }

  #covid_hotel #alert-description a:hover {
    color: #1A5C87;
    text-decoration: none;
  }


  



  
</style>

<div id="covid_hotel">
<div id="alert-description">
  {{-- <i class="icon-info-circled-1" style="font-size: 20px; padding-right: 5px;"><span style="display:none;">Info</span></i>  --}}
  <span id="alert">COVID-19</span>{!!$testo_covid_banner!!}
</div>
</div>