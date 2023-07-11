@if (!is_null($serviziCovidResult) && !empty($serviziCovidResult))
<div id="serviziCovid" style="border-color: {{$color}}">
    @foreach($serviziCovidResult as $key => $servizi)
        <div class="serviziCovid">
            <b>{{$key}}</b>
            @foreach($servizi as $servizio)
                <li><i class="icon-ok"></i> {{$servizio}}</li>
            @endforeach
        </div> 
    @endforeach
</div>
@endif