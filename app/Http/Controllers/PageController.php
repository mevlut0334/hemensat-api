<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function subscription()
    {
        return view('subscriptions.index');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function privacyPolicy()
    {
        return view('pages.privacy-policy');
    }

    public function kvkk()
    {
        return view('pages.kvkk');
    }
}
