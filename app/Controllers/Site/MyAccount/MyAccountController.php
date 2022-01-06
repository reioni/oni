<?php
namespace App\Controllers\Site\MyAccount;

use Src\Classes\{
	Request,
	Controller
};
use App\Classes\Cart;
use App\Models\{
	Client,
	ClientAddress,
	ClientCard
};

class MyAccountController extends Controller{
	private $client;

	public function __construct(){
		$this->client = auth('site');

		if(!$this->client)
			abort(404);
	}

	public function index(){;
		$cart = new Cart();
		$client = $this->client;

		return view('site.myaccount.index', compact('client', 'cart'));
	}

	public function logout(){
		session()->remove(config('app.url'));
		redirect(route('site.login'));
	}
}