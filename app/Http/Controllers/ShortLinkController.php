<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    public function redirect($code)
    {
        $shortLink = ShortLink::where('short_code', $code)->firstOrFail();
        return redirect($shortLink->original_url);
    }
}
