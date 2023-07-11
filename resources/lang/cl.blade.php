var $cl_msg 	= '{{ trans('labels.cl_msg') }}';
var $cl_ok 		= '{{ trans("labels.cl_ok") }}';
var $cl_not 	= '{{ trans("labels.cl_not") }}';
var $cl_info	= '{{ trans("labels.cl_info") }}';

@if ($locale == 'it')
	var $mail_ok 			= '<div class="alert alert-ok">{{ trans('listing.mail_ok') }}</div>';
	var $mail_ko 			= '<div class="alert alert-danger">{{ trans('listing.mail_ko') }}</div>';
	var $attendi 			= '<div class="alert alert-info">{{ trans('labels.attendi') }}</div>';
	var $privacy_ko 		= '<div class="alert alert-danger">{{ trans('labels.privacy_ko') }}</div>';
	var $email_ko 			= '<div class="alert alert-danger">{{ trans('labels.email_ko') }}</div>';
	var $iscrizione_route 	= '{{Utility::getUrlWithLang($locale, '/iscrizione_newsletter')}}';
@endif


