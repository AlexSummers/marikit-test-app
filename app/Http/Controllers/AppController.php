<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Route;

class AppController extends Controller {

    /**
     * @param string $path
     * @return false|string
     */
    private function render($path) {
        $rendererSource = File::get(base_path('node_modules/vue-server-renderer/basic.js'));
        $appSource      = File::get(public_path('js/entry-server.js'));
        $v8             = new \V8Js();

        ob_start();
        $js =
<<<EOT
var process = { env: { VUE_ENV: "server", NODE_ENV: "production" } }; 
this.global = { process: process }; 
var url = "$path";
EOT;
        $v8->executeString($js);
        $v8->executeString($rendererSource);
        $v8->executeString($appSource);
        return ob_get_clean();
    }

    /**
    * @param Request $request
    * @return \Illuminate\View\View
    */
    public function get(Request $request) {
        $ssr = $this->render($request->path());
        return view('app', ['ssr' => $ssr]);
    }
}
