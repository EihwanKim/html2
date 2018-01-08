<?php

namespace App\Http\Controllers;

use App\CoinMaster;
use App\Configs;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Form;

class SettingController extends Controller
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

    public function index() {

        $coins = CoinMaster::all();
        return view('setting', compact('coins'));
    }

    public function config_form(Request $request) {
        $configs = Configs::all();
        return view('config/common_form', compact('configs'));
    }

    public function config_submit(Request $request) {

        $configs = Configs::all();
        $validate_rule = [];
        foreach ($configs as $config) {
            $validate_rule[$config->name] = $config->validation;
        }
        $this->validate($request, $validate_rule);

        $data = $request->input();

        foreach($configs as $config) {
            Configs::where('id', $config->id)->update(['value' => $data[$config->name]]);
        }

        return redirect(route('setting_config_form'))->with('success','save completed!!');;
    }

    public function coin_form(Request $request, $type) {

        $coin = CoinMaster::whereCoinType($type)->first();
        return view('config/coin_form', compact('coin'));
    }

    public function coin_submit(Request $request, $type) {

    }
}
