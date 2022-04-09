<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\App;
use Laravel\Dusk\Browser;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $apps = App::all();
        return view('home', [
            'apps' => $apps,
        ]);
    }

    public function connect()
    {
        $clientid = env('SP_CLIENT_ID');
        $apikey = env('SP_API_KEY');
        $ch = curl_init("https://api.serverpilot.io/v1/apps");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$clientid:$apikey");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);

        // echo json_encode(json_decode($result), JSON_PRETTY_PRINT);

        $data = json_decode($result)->data;
        foreach($data as $app) {
            $temp = App::firstOrNew([
                'sp_id' => $app->id,
            ]);
            $temp->app_name = $app->name;
            $temp->sp_user_id = $app->sysuserid;
            $temp->domains = json_encode($app->domains);
            $temp->sp_server_id = $app->serverid;
            $temp->runtime = $app->runtime;
            $temp->status = -1;
            $temp->latest_screenshot = '';
            $temp->save();
        }

        return Redirect::route('home');  
    }

    public function status()
    {
        $apps = App::all();
        foreach($apps as $app) {
            // Assume app status good
            $app->status = 1;
            $resolved_domain = '';
            $domains = json_decode($app->domains);
            foreach($domains as $domain) {
                $ch = curl_init($domain);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                $resolved_domain = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
                curl_close($ch);
                if($httpcode!=200) {
                    $app->status = 0;
                }
            }
            $filename = $app->app_name.time().'.png';
            //$browser = new Browser();
            //$browser->visit($domains[0])
            //        ->storeSource($filename);
            if($app->status == 1) {
                $client = \Symfony\Component\Panther\Client::createChromeClient();
                $crawler = $client->request('GET', $resolved_domain);
                $client->waitFor('body');
                sleep(2);
                $client->takeScreenshot($filename); 
                $app->latest_screenshot=$filename;
            }
            $app->save();
        }

        return Redirect::route('home');
    }

    public function details($id)
    {
        $app = App::findOrFail($id);
        return view('details', [
            'app' => $app,
        ]);
    }

}
