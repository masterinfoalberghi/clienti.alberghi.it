<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


/**
 
https://api.sendgrid.com/api/blocks.count.json?api_user=infoalberghi.com&api_key=yKW7KCYz

https://api.sendgrid.com/api/blocks.get.json?api_user=infoalberghi.com&api_key=yKW7KCYz&date=1&days=7

https://api.sendgrid.com/api/bounces.get.json?api_user=infoalberghi.com&api_key=yKW7KCYz&date=1&days=7

 * */ 





 /**
  
 While some in the industry use terms like “hard bounce” and “soft bounce” to describe messages that were refused by the receiving server, SendGrid’s terminology separates returned messages into bounces and blocks.

  Bounces occur when the receiving server returns to us a code that indicates that the reason for the refusal is a permanent issue with that server or recipient address.

  Similar to what you may have heard referred to as a “soft bounce,” blocked emails are ones where the receiving server returned to us a reason for refusal that indicates a non-permanent rejection for that address. In short, it is a rejection of that message and not an indication of the quality of the address being sent to. Seeing lots of blocked messages is a good indicator that your sending reputation might be suffering.

  A deferred event, or deferral, is simply an event SendGrid has received back from the receiving server that tells us that the receiving server has temporarily limited access to their system.It does not mean that your message will not be delivered. Rather, it is a signal that your message will not be delivered immediately.

  */





class SendgridController extends AdminBaseController
{
  // private $host = "";
  // private $user = "";
  // private $key = "";


  //   public function __construct()
  //   {
    
  //     $this->host = "https://api.sendgrid.com/api";
  //     $this->user = "infoalberghi.com";
  //     $this->key = "yKW7KCYz";
        
  //   }




  //   private function _make_url($uri="bounces.count.json", $param="days=1")
  //     {
  //       $url =  $this->host.'/'.$uri.'?api_user='.$this->user.'&api_key='.$this->key.'&date=1&'.$param;

  //       return $url;
  //     }
  
    
  //   private function _get_call($url)
  //     {
  //     	//open connection
	// 		$ch = curl_init();

	// 		//set the url, number of POST vars, POST data
	// 		curl_setopt($ch,CURLOPT_URL, $url);
		
	// 		//So that curl_exec returns the contents of the cURL; rather than echoing it
	// 		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

	// 		//execute post
  //     $result = curl_exec($ch);
      
	// 		return $result;
  //     }

  //   public function dashboard(Request $request)
  //     {


  //     // bounces count
  //     $url = $this->_make_url('bounces.count.json');
  //     $bounces_count = json_decode($this->_get_call($url));

      
  //     // block count
  //     $url = $this->_make_url('blocks.count.json');
  //     $blocks_count = json_decode($this->_get_call($url));


  //     return view('admin.sendgrid.dashboard', compact('bounces_count', 'blocks_count'));


  //     }

    public function blocks(Request $request)
      {
      
      $days = 2;
      $param="days=$days";
      // block list
      $url = $this->_make_url_sd('blocks.get.json', $param);
      $elements = array_reverse(json_decode($this->_get_call_sd($url)));
      
      $title = "Blocks ultimi $days giorni";
      $desc = "Blocked emails (soft bounce) are ones where the receiving server returned to us a reason for refusal that indicates a non-permanent rejection for that address. In short, it is a rejection of that message and not an indication of the quality of the address being sent to. Seeing lots of blocked messages is a good indicator that your sending reputation might be suffering.";
      
      return view('admin.sendgrid.list', compact('title','desc', 'elements'));

      }




    public function bounces(Request $request)
      {

      $days = 7;
      $param="days=$days";
     
      // bounces lists
      $url = $this->_make_url_sd('bounces.get.json', $param);
      $elements = array_reverse(json_decode($this->_get_call_sd($url)));
      
      $title = "Bounces ultimi $days giorni";
      $desc = "Bounces (hard bounces) occur when the receiving server returns to us a code that indicates that the reason for the refusal is a permanent issue with that server or recipient address.";

      return view('admin.sendgrid.list', compact('title','desc', 'elements'));


      }
}
