<?php
namespace App\Controllers\Site;

use Src\Classes\{
	Request,
	Controller
};
use App\Models\Notice;

class NoticeController extends Controller{
	private $notice;

	public function __construct(){
		$this->notice = new Notice();
	}

	public function index(){
		$request = new Request();

		$builder = $request->except('page');
		$page = $request->input('page') ?? 1;
		$limit = config('paginate.limit');
		$pages = ceil($this->notice->count() / $limit);

		$notices = $this->notice->where('visible', true)->orderBy('id', 'DESC')->offset(($page - 1) * $limit)->limit($limit)->get();

		return view('site.notices.index', compact('notices', 'page', 'pages', 'builder'));
	}

	public function show($slug){
		$notice = $this->notice->where('visible', true)->where('slug', $slug)->firstOrFail();

		$notice->visits++;
		$notice->save();

		return view('site.notices.show', compact('notice'));
	}
}