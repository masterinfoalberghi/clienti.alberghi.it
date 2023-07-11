@extends('templates.admin')

@section('title')
  Inserisci una o più file csv con rating degli hotel
@endsection

@section('onheadclose')
<link rel="stylesheet" type="text/css" href="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.css')}}" />

@stop

@section('content')
      <div class="row">
      <div class="form-group">
        <div class="col-sm-5">
          <div class="panel panel-default" data-collapsed="0">
            <div class="panel-heading"><div class="panel-title">CSV</div></div>
            <div class="panel-body">

              <div class="dropzone" id="dropzoneFileUpload">
              
              </div>
               
             
              <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
              
              <br>
              <div id="dze_info">
                <br> 
                <div class="panel panel-default"> 
                  <div class="panel-heading"> 
                    <div class="panel-title">Dropzone Uploaded Files Info</div> 
                  </div> 
                  <div class="panel-body with-table">
                    <table class="table table-bordered"> 
                      <thead> 
                        <tr><th width="40%">File name</th><th>Size</th><th>Status</th></tr> 
                      </thead>
                      <tbody id="responses">
    
                      </tbody> 
                      <tfoot> 
                        <tr><td colspan="4"></td> </tr> 
                      </tfoot> 
                    </table>
                  </div> 
                </div> 
              </div>            
            </div>
          </div>
        </div>
    </div>
    </div>

@endsection

@section('onbodyclose')

<script type="text/javascript" src="{{Utility::assets('/vendor/neon/js/dropzone/dropzone.js')}}"></script>
  
  
  {{-- Alternatively you can create dropzones programmaticaly (even on non form elements) by instantiating the Dropzone class --}}
  <script type="text/javascript">
    var baseUrl = "{{ url('/') }}";
    var token = "{{ Session::token() }}";
    
    Dropzone.autoDiscover = false;
    
    var myDropzone = new Dropzone("div#dropzoneFileUpload", {
         url: baseUrl+"/admin/rating/uploadCsv",
         previewTemplate: '<div class="dz-preview dz-file-preview"><div class="dz-details"><div class="dz-filename"><span data-dz-name></span></div><div class="dz-size" data-dz-size></div></div><div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div><div class="dz-success-mark"><span>✔</span></div><div class="dz-error-mark"><span>✘</span></div><div class="dz-error-message"><span data-dz-errormessage></span></div></div>',
         params: {
            _token: token,
          }
     });

    myDropzone.on("success", function(file, response) {
      jQuery("tbody#responses").append('<tr style="background-color:green; color:#fff;"><td>'+file.name+'</td><td>'+file.size+'</td><td>'+response+'</td></tr>');
     });
    
    myDropzone.on("error", function(file, error) {
      jQuery("tbody#responses").append('<tr style="background-color:red; color:#fff;"><td>'+file.name+'</td><td>'+file.size+'</td><td>'+error+'</td></tr>');
     });

  </script>  

@endsection

