<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aviso;
use App\Models\AvisoView;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AvisoController extends Controller
{
    public function index(Request $request){
        $data = Aviso::orderBy('id', 'desc')
        ->when(!empty($request->search), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('titulo', 'LIKE', "%$request->search%");
            });
        })
        ->paginate(getenv("PAGINATE"));

        return view('avisos/index', compact('data'));
    }

    public function new(){
        return view('avisos/create');
    }

    public function edit($id){
        $item = Aviso::findOrFail($id);
        return view('avisos/edit', compact('item'));
    }

    public function view($id){
        $item = Aviso::findOrFail($id);
        $user = session('user_logged');

        $avisoView = AvisoView::where('aviso_id', $id)
        ->where('aluno_id', $user['aluno']->id)->first();
        if($avisoView == null){
            AvisoView::create([
                'aviso_id' => $id,
                'aluno_id' => $user['aluno']->id
            ]);
        }
        return view('avisos/view', compact('item'));
    }

    public function delete($id){
        $item = Aviso::findOrFail($id);

        try {
            if($item->imagem != ""){
                if(file_exists(public_path('avisos/').$item->imagem)){
                    unlink(public_path('avisos/').$item->imagem);
                }
            }
            $item->views()->delete();
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/aviso');
        } catch (\Exception $e) {
                // echo $e->getMessage();
                // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/aviso');
        }

    }

    public function store(Request $request){
        $this->_validate($request);

        try{
            $isPushSend = DB::transaction(function () use ($request) {
                $fileName = "";

                if($request->hasFile('file')){
                    $file = $request->file('file');
                    
                    $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
                    $file->move(public_path('avisos'), $fileName);
                }
                $request->merge(['imagem' => $fileName]);
                $inputs = $request->all();
                $aviso = Aviso::create($inputs);

                return $this->sendPush($aviso);

            });

            if($isPushSend == 1){
                session()->flash("flash_sucesso", "Aviso registrado, push enviado para todos!");
            }else{
                session()->flash("flash_sucesso", "Aviso registrado, porém push não foi enviado!");
            }
            return redirect('/aviso');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/aviso');
        }
    }

    private function sendPush($aviso){

        try{
            $data = [
                'heading' => [
                    "en" => $aviso->titulo
                ],
                'content' => [
                    "en" => $aviso->texto
                ],
                'image' => '',
            // 'link' => '',
            ];

            $fields = [
                'app_id' => getenv('ONE_SIGNAL_APP_ID'),
                'contents' => $data['content'],
                'headings' => $data['heading'],
                'large_icon' => getenv('APP_URL').'/images/push.png',
                'small_icon' => 'notification_icon',

            ];

            $fields['included_segments'] = array('All');

            $fields['chrome_web_image'] = getenv('APP_URL').'/images/push.png';

            if($aviso->imagem != ""){
                $fields['chrome_web_image'] = getenv('APP_URL') . '/avisos/' . $aviso->imagem;
            }
            $fields = json_encode($fields);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                'Authorization: Basic '.getenv('ONE_SIGNAL_KEY')));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $response = curl_exec($ch);
            curl_close($ch);

            return 1;

        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Aviso::findOrFail($id);

        try{
            $isPushSend = DB::transaction(function () use ($request, $item) {

                $fileName = "";
                if($request->hasFile('file')){

                    if($item->imagem != ""){
                        if(file_exists(public_path('avisos/').$item->imagem)){
                            unlink(public_path('avisos/').$item->imagem);
                        }
                    }
                    $file = $request->file('file');

                    $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
                    $file->move(public_path('avisos'), $fileName);

                }
                $request->merge(['imagem' => $fileName]);

                $item->fill($request->all())->save();
                $item = Aviso::findOrFail($item->id);
                return $this->sendPush($item);

            });

            if($isPushSend == 1){
                session()->flash("flash_sucesso", "Aviso registrado, push enviado!");
            }else{
                session()->flash("flash_sucesso", "Aviso registrado, porém push não foi enviado!");
            }
            return redirect('/aviso');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/aviso');
        }
    }


    private function _validate(Request $request, $id = 0){

        $rules = [
            'titulo' => 'required|max:50',
            'texto' => 'required'
        ];

        $messages = [
            'titulo.required' => 'Título é obrigatório.',
            'titulo.max' => '50 caracteres permitidos.',
            'texto.required' => 'Texto é obrigatório.',

        ];
        $this->validate($request, $rules, $messages);
    }
}
