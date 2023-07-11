<img src="{{Utility::assets('others/logo-small.png', true)}}" alt="Info Alberghi S.r.l" />
			
<p>
  Via Alessandro Gambalunga, 81/A<br />
  47921 - Rimini (RN)<br />
  {{ trans('labels.footer_tel_what') }} 0541 29187<br />
  Email box[at]info-alberghi.com<br />
  P.IVA 03479440400<br />
  {{ trans('hotel.diritti') }}<br />
  @if ($locale == 'it')
    <a href="{{Utility::getUrlWithLang($locale, "/informativa-privacy-gdpr.php")}}" class="reverse" target="_blank">Privacy policy</a>&nbsp;&nbsp;&nbsp;
    <a href="{{Utility::getUrlWithLang($locale, "/cookie-policy-gdpr.php")}}" class="reverse" target="_blank">Cookie policy</a>&nbsp;&nbsp;&nbsp;
    <a class="reverse" href="{{Utility::getUrlWithLang($locale, "/informazioni.php")}}">Altre info</a>
  @elseif ($locale == 'de')
    <a href="{{Utility::getUrlWithLang($locale, "/datenschutzerklarung-gdpr.php")}}" class="reverse" target="_blank">Datenschutzerklärung</a>&nbsp;&nbsp;&nbsp;
    <a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
  @elseif ($locale == 'fr')
    <a href="{{Utility::getUrlWithLang($locale, "/politique-de-confidentialite-gdpr.php")}}" class="reverse" target="_blank">Politique de confidentialité</a>&nbsp;&nbsp;&nbsp;
    <a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
  @else
    <a href="/ing/privacy-policy-gdpr.php" class="reverse" target="_blank">Privacy policy</a>&nbsp;&nbsp;&nbsp;
    <a href="/ing/cookie-policy-gdpr.php" class="reverse" target="_blank">Cookie policy</a>
  @endif
</p>

<a href="{{url('')}}/admin" target="_blank" class="btn btn-cyano">Area riservata strutture alberghiere</a>