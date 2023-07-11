<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsLetterRequest;
use App\NewsletterLinks;
use App\Utility;
use Illuminate\Http\Request;
use SessionResponseMessages;


class NewsLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newsletterLinks = NewsletterLinks::OrderBy('data_invio','desc')->get();

        $newsletterPublished_ids = NewsletterLinks::OrderBy('data_invio','desc')->limit(Utility::getMaxNewsletterLinks())->pluck('id')->toArray();

        $data['newsletterLinks'] = $newsletterLinks;
        $data['newsletterPublished_ids'] = $newsletterPublished_ids;

        return view('admin.newsletter_index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        
        return view('admin.newsletter_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsLetterRequest $request)
    {
        $data = $request->except('data_invio');
        $data['data_invio'] = Utility::getCarbonDate($request->get('data_invio'));
        
        $newsletterLink = NewsletterLinks::create($data);
        SessionResponseMessages::add("success", "Inserimento effettuato con successo.");
        
        return SessionResponseMessages::redirect("admin/newsletterLink", $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $newsletterLink = NewsletterLinks::find($id);
        $data['newsletterLink'] = $newsletterLink;
        $data['showButtons'] = 1;
        return view('admin.newsletter_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
    $newsletterLink = NewsletterLinks::find($id);
    $data = $request->except('data_invio','_method','_token','submit');
    $data['data_invio'] = Utility::getCarbonDate($request->get('data_invio'));
    
    $newsletterLink = NewsletterLinks::where('id',$id)->update($data);

    SessionResponseMessages::add("success", "Aggiornamento effettuato con successo.");
    
    return SessionResponseMessages::redirect("admin/newsletterLink", $request);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
      {

      $newsletterLink = NewsletterLinks::find($id);
      $newsletterLink->delete();

      SessionResponseMessages::add("success", "Link eliminato con successo.");
  
      return SessionResponseMessages::redirect("admin/newsletterLink", $request);

      }
}
