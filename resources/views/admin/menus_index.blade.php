@extends('templates.admin')

@section('title')
Menu tematici
@endsection

@section('content')
  @foreach($data["macrolocalita"] as $macrolocalita)
    
    @if(gettype(json_decode($macrolocalita->nome)) == "object")
      <h3><a href="{{ url("admin/menus/".$macrolocalita->id."/edit") }}">{{ json_decode($macrolocalita->nome)->it }}</a></h3>
    @else 
      <h3><a href="{{ url("admin/menus/".$macrolocalita->id."/edit") }}">{{ $macrolocalita->nome }}</a></h3>
    @endif

    <table class="table table-hover table-bordered table-responsive datatable dataTable" >
      <thead>
        <tr>
          <th>Lingua</th>
          @foreach (['servizi','offerte','famiglia','trattamenti','parchi','visibilita'] as $type)
              <th>n° voci {{$type}}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach (Langs::getAll() as $lang)
          <tr>
            <td>
              <img src="{{ Langs::getImage($lang) }}" alt="{{ $lang }}"> {{ Langs::getName($lang) }}
            </td>
            @foreach (['servizi','offerte','famiglia','trattamenti','parchi','visibilita'] as $type)
              <?php $conteggio = 0; ?>
              @if (!empty($data["records"][$macrolocalita->id][$lang][$type]))
                <?php $conteggio += count($data["records"][$macrolocalita->id][$lang][$type]); ?>
              @endif
              @if (!empty($data["records"][0][$lang][$type]))
                <?php $conteggio += count($data["records"][0][$lang][$type]); ?>
              @endif
              <td>
                {{ $conteggio == 0 ? "-" :  $conteggio }}
              </td>
            @endforeach
          </tr>
        @endforeach
      </tbody>
    </table>


  @endforeach
@endsection