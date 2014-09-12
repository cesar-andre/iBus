<?php
 
 
class CompanhiasController extends BaseController{
 
	public function index(){
		switch (Auth::user()->credenciais_id) {
			case 1:
			# Administrador do iBus
			$companhias = Companhias::all();
			break;
			case 2:
			# Gerente
			$companhias = Companhias::where('id', Auth::user()->companhias_id)->get();
			break;
			case 3:
			# Operador
			$companhias = Companhias::where('id', Auth::user()->companhias_id)->get();
			break;
		}
 
		return View::make('companhias.index')->with('companhias', $companhias);
	}
 
	public function criar(){
 
		if(Auth::user()->credenciais_id == 1){
			return View::make('companhias.criar');
 
		} else{
			return Redirect::back()->withInput()->with('msgErro', 'Você não tem permissão para criar empresas');
		}
 
	}
 
	public function create(){
 
		$regras = array(
			'nome' => 'required',
			'email' => 'required',
			'telefone' => 'required',
			'cnpj' => 'required',
			'ie' => 'required',
			'responsavel' => 'required',
			'logo' => 'mimes:jpeg,jpg,png');
 
		$validator = Validator::make(Input::all(), $regras);
 
		if ($validator->fails()) {
			return Redirect::back()->withInput()->with('msgErro', 'Não foi possível criar uma compania, verifique se todos os campos estão corretamente preenchidos');
		} else{
			$companhia = New Companhias;
 
			$companhia->nome = Input::get('nome');
			$companhia->email = Input::get('email');
			$companhia->telefone = Input::get('telefone');
			$companhia->cnpj = Input::get('cnpj');
			$companhia->ie = Input::get('ie');
			$companhia->responsavel = Input::get('responsavel');
 
			$arquivo = Input::file('logo');
			if ($arquivo) {
				$destino = public_path().'/images/logos/';
				$extensao =$arquivo->getClientOriginalExtension();
				$nomearquivo = "logo".time().".".$extensao;
				$uploadSuccess = Input::file('logo')->move($destino, $nomearquivo);
 
				$companhia->logo = $nomearquivo;
			}
 
			$companhia->save();
			return Redirect::to('/companhias')->with('msgSucesso', 'Companhia Cadastrada com sucesso');
 
 
 
		}
 
	}
 
	public function editar(){
 
		if (Auth::user()->credenciais_id < 3) {
			$companhia = Companhias::find(Route::input('id'));
			return View::make('companhias.editar')->with('companhia', $companhia);
 
			
		}else{
			return Redirect::back()->withInput()->with('msgErro', 'Você não tem permissão para editar empresas!');
		}
 
	}
 
	public function edit(){
		$regras = array(
			'nome' => 'required',
			'email' => 'required',
			'telefone' => 'required',
			'cnpj' => 'required',
			'ie' => 'required',
			'responsavel' => 'required',
			'logo' => 'mimes:jpeg,jpg,png');
 
		$validator = Validator::make(Input::all(), $regras);
 
		if ($validator->fails()) {
			return Redirect::back()->withInput()->with('msgErro', 'Não foi possível editar a compania, verifique se todos os campos estão corretamente preenchidos');
		}else{
			$companhia = Companhias::find(Input::get('i'));
 
			$companhia->nome = Input::get('nome');
			$companhia->email = Input::get('email');
			$companhia->telefone = Input::get('telefone');
			$companhia->cnpj = Input::get('cnpj');
			$companhia->ie = Input::get('ie');
			$companhia->responsavel = Input::get('responsavel');
 
			$arquivo = Input::file('logo');
			if ($arquivo) {
				$destino = public_path().'/images/logos/';
				$extensao =$arquivo->getClientOriginalExtension();
				$nomearquivo = "logo".time().".".$extensao;
				$uploadSuccess = Input::file('logo')->move($destino, $nomearquivo);
 
				$companhia->logo = $nomearquivo;
			}
 
			$companhia->save();
			return Redirect::to('/companhias')->with('msgSucesso', 'Companhia editada com sucesso');
 
		}
 
	}
 
	public function deletar(){
 
		if (Auth::user()->credenciais_id < 2) {
 
			$companhia = Companhias::find(Route::input('id'));
			return View::make('companhias.deletar')->with('companhia', $companhia);
			
		}else{
			return Redirect::back()->withInput()->with('msgErro', 'Você não tem permissão para deletar empresa!');
		}
 
	}
 
	public function delete(){
 
		if (Auth::user()->credenciais_id == 1) {
			$companhia = Companhias::find(Input::get('i'));
 
			$companhia->deletar_cascade();
			return Redirect::to('/companhias')->with('msgSucesso', 'Companhia deletada com sucesso');
 
		}
 
	}
 
 
 
}
