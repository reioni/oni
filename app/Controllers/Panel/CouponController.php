<?php
namespace App\Controllers\Panel;

use Src\Classes\{
	Request,
	Controller
};
use App\Models\{
	Coupon,
	Role
};

class CouponController extends Controller{
	private $coupon;

	public function __construct(){
		$this->coupon = new Coupon();

		$this->coupon->verifyPermission('view.coupons');
	}

	public function index(){
		$request = new Request();

		$builder = $request->except('page');
		$page = $request->input('page') ?? 1;
		$search = $request->input('search');
		$pages = ceil($this->coupon->count() / config('paginate.limit'));

		$coupons = $this->coupon->search($page, $search);

		return view('panel.coupons.index', compact('coupons', 'pages', 'builder'));
	}

	public function create(){
		$this->coupon->verifyPermission('create.coupons');

		return view('panel.coupons.create');
	}

	public function store(){
		$this->coupon->verifyPermission('create.coupons');

		$request = new Request();
		$data = $request->all();

		$this->validator($data, $this->coupon->rolesCreate, $this->coupon->messages);
		$data['code'] = mb_strtoupper($data['code']);
		if(empty($data['expiration'])){
			unset($data['expiration']);
		}

		$coupon = $this->coupon->create($data);

		if($coupon){
			redirect(route('panel.coupons.create'), ['success' => 'Cupom cadastrado com sucesso']);
		}

		redirect(route('panel.coupons.create'), ['error' => 'Cupom NÃO cadastrado, Ocorreu um erro no processo de cadastro!']);
	}

	public function edit($id){
		$this->coupon->verifyPermission('edit.coupons');

		$coupon = $this->coupon->findOrFail($id);

		return view('panel.coupons.edit', compact('coupon'));
	}

	public function update($id){
		$this->coupon->verifyPermission('edit.coupons');
		
		$coupon = $this->coupon->findOrFail($id);

		$request = new Request();
		$data = $request->all();

		$this->validator($data, $coupon->rolesUpdate, $coupon->messages);
		$data['code'] = mb_strtoupper($data['code']);
		if(empty($data['expiration'])){
			unset($data['expiration']);
		}

		if($coupon->update($data)){
			redirect(route('panel.coupons.edit', ['id' => $coupon->id]), ['success' => 'Cupom editado com sucesso']);
		}

		redirect(route('panel.coupons.edit'), ['error' => 'Cupom NÃO editado, Ocorreu um erro no processo de edição!']);
	}

	public function destroy($id){
		$this->coupon->verifyPermission('delete.coupons');
		$coupon = $this->coupon->findOrFail($id);

		if($coupon->delete()){
			redirect(route('panel.coupons'), ['success' => 'Cupom deletado com sucesso']);
		}

		redirect(route('panel.coupons'), ['error' => 'Cupom NÃO deletado, Ocorreu um erro no processo de exclusão!']);
	}
}