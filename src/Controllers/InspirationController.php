<?php
namespace Zdslab\Laravelinit\Controllers;

use Illuminate\Http\Request;
use Zdslab\Laravelinit\Inspire;

class InspirationController
{
    public function __invoke(Inspire $inspire) {
        $quote = $inspire->justDoIt();

        return view('laravelinit::index', compact('quote'));
    }
}