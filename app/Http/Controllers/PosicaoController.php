<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Faixa;
use App\Models\Posicao;
use App\Models\Categoria;
use App\Models\PosicaoView;
use App\Models\ComentarioVideo;
use App\Models\PosicaoVideo;
use App\Services\GoogleDriveService;
use App\Services\GoogleClient;
use Illuminate\Support\Str;

class PosicaoController extends Controller
{

    public function __construct(){
    }

    public function index(Request $request){
        $data = Posicao::
        select('posicaos.*')
        ->when(!empty($request->search), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('nome', 'LIKE', "%$request->search%");
            });
        })
        ->when(!empty($request->categoria_id), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                return $quer->where('categoria_id', $request->categoria_id);
            });
        })
        ->when(!empty($request->status), function ($q) use ($request) {
            return  $q->where(function ($quer) use ($request) {
                $status = $request->status == -1 ? 0 : $request->status;
                return $quer->where('status', $status);
            });
        })
        ->when(!empty($request->faixa_id), function ($q) use ($request) {
            return $q->where('faixa_id', $request->faixa_id);
        })
        ->when(!empty($request->ordem), function ($q) use ($request) {
            return $q->orderBy($request->ordem, 'desc');
        })
        ->orderBy('id', 'desc')
        ->paginate(getenv("PAGINATE"));

        $faixas = Faixa::all();
        $categorias = Categoria::all();

        return view('posicao/index', compact('data', 'categorias', 'faixas'));
    }

    public function new(){
        $categorias = Categoria::all();
        $faixas = Faixa::all();
        return view('posicao/create', compact('categorias', 'faixas'));
    }

    public function edit($id){
        $item = Posicao::findOrFail($id);
        $categorias = Categoria::all();
        $faixas = Faixa::all();
        return view('posicao/edit', compact('categorias', 'faixas', 'item'));
    }

    public function delete($id){
        $item = Posicao::findOrFail($id);

        try {
            if($item->imagem != ""){
                if(file_exists(public_path('posicoes/').$item->imagem)){
                    unlink(public_path('posicoes/').$item->imagem);
                }
            }
            $item->videos()->delete();
            $item->views()->delete();
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");

            return redirect('/posicao');
        } catch (\Exception $e) {
            echo $e->getMessage();
            die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/posicao');
        }
    }

    public function deleteVideo($id){
        $item = PosicaoVideo::findOrFail($id);
        try {

            $item->delete();
            session()->flash("flash_sucesso", "Registro removido");
            return redirect()->back();
        } catch (\Exception $e) {
            // echo $e->getMessage();
            // die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect()->back();
        }
    }

    public function store(Request $request){
        $this->_validate($request);

        $fileName = "";
        if($request->hasFile('file')){
            $file = $request->file('file');

            $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('posicoes'), $fileName);

        }
        try{

            $user = session('user_logged');

            $video = $request->file('video_url');
            $videoName = "";
            if($video){
                $videoName = Str::random(20) . "." . $video->getClientOriginalExtension(); 
                $video->move(public_path('video_temp'), $videoName);
            }

            $request->merge([
                'status' => $request->status ? true : false,
                'imagem' => $fileName,
                'descricao' => $request->descricao ?? '',
                'aluno_id' => $user['aluno']->id,
                'video_temp' => $videoName
            ]);


            $inputs = $request->all();
            $p = Posicao::create($inputs);
            if($video){
                $this->driveUpload();
            }

            if($request->upload_link){
                PosicaoVideo::create([
                    'posicao_id' => $p->id,
                    'url' => $request->link,
                    'tipo' => $request->tipo
                ]);
            }

            session()->flash("flash_sucesso", "Posição registrada!");
            return redirect('/posicao');
        }catch(\Exception $e){
            echo $e->getMessage();
            die;
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/posicao');
        }
    }

    private function driveUpload(){

        if(!session_id()) session_start(); 
        $google = new GoogleClient();

        if(!$google->isLoggedIn()){ 

            header('Location: '.$google->getAuthURL());
            exit;
        }
    }

    public function update(Request $request, $id){
        $this->_validate($request, $id);
        $item = Posicao::findOrFail($id);

        $fileName = "";
        if($request->hasFile('file')){

            if($item->imagem != ""){
                if(file_exists(public_path('posicoes/').$item->imagem)){
                    unlink(public_path('posicoes/').$item->imagem);
                }
            }
            $file = $request->file('file');

            $fileName = Str::random(20) . "." . $file->getClientOriginalExtension();
            $file->move(public_path('posicoes'), $fileName);

        }
        try{
            DB::transaction(function () use ($request, $fileName, $item) {

                $request->merge([
                    'status' => $request->status ? true : false,
                    'imagem' => $fileName,
                    'descricao' => $request->descricao ?? '',
                ]);

                $item->fill($request->all())->save();

            });
            session()->flash("flash_sucesso", "Posição editada!");
            return redirect('/posicao');
        }catch(\Exception $e){
            // echo $e->getMessage();
            // die;            
            session()->flash("flash_erro", "Algo deu errado!");
            return redirect('/posicao');
        }
    }

    private function _validate(Request $request, $id = 0){

        $rules = [
            'nome' => ['required', 'max:50', \Illuminate\Validation\Rule::unique('posicaos')->ignore($id)],
            'categoria_id' => 'required',
            'faixa_id' => 'required',
            'link' => $request->upload_link ? 'required' : '',
        ];

        $messages = [
            'nome.required' => 'Nome é obrigatório.',
            'faixa_id.required' => 'Faixa é obrigatório.',
            'categoria_id.required' => 'Categoria é obrigatório.',
            'nome.required' => '50 caracteres permitidos.',
            'nome.unique' => 'Nome já cadastrado.',
            'link.unique' => 'Link é obrigatório.',

        ];
        $this->validate($request, $rules, $messages);
    }



    public function video(Request $request){
        if($request->code){
            $posicao = Posicao::where('video_temp', '!=', '')->first();

            $google = new GoogleClient();
            $google->authenticate($request->code);
            $google->initDriveService();
            $mime_type = mime_content_type(public_path('/video_temp/').$posicao->video_temp);
            $data = $google->uploadFile(getenv("FOLDER_ID"), public_path('/video_temp/').$posicao->video_temp, $posicao->video_temp, $mime_type);

            $posicao->video_temp = '';
            $posicao->save();
            PosicaoVideo::create([
                'posicao_id' => $posicao->id,
                'url' => $data,
                'tipo' => 'google_drive'
            ]);
            
            $this->clearTempFolderVideo();
            session()->flash("flash_sucesso", "Posição registrada com video para Google drive!");

            return redirect('/posicao');
        }else{

        }
    }

    // public function video(Request $request){
    //     if($request->code){

    //         $posicao = Posicao::orderBy('id', 'desc')->first();

    //         $googleDriveService = new GoogleDriveService(); 
    //         $data = $googleDriveService->GetAccessToken(
    //             getenv("GOOGLE_CLIENT_ID"), 
    //             getenv("REDIRECT_URI"), 
    //             getenv("GOOGLE_CLIENT_SECRET"), 
    //             $request->code); 

    //         $access_token = $data['access_token'];

    //         if(!empty($access_token)){ 

    //             $file_content = file_get_contents(public_path('/video_temp/').$posicao->video_temp);
    //             $mime_type = mime_content_type(public_path('/video_temp/').$posicao->video_temp);
    //             $drive_file_id = $googleDriveService->UploadFileToDrive($access_token, $file_content, 
    //                 $mime_type);

    //             if($drive_file_id){ 
    //                 $file_meta = [ 
    //                     'name' => basename($posicao->video_temp)
    //                 ]; 
    //                 $drive_file_meta = $googleDriveService->UpdateFileMeta($access_token, $drive_file_id, 
    //                     $file_meta, '1R8u2FKq-0zk5zZZ_3kaLeydjiYKXYpfm', $posicao->video_temp, $file_content, $mime_type); 
    //                 PosicaoVideo::create([
    //                     'posicao_id' => $posicao->id,
    //                     'url' => "https://drive.google.com/open?id=".$drive_file_meta['id']
    //                 ]);
    //             }
    //         }

    //         $this->clearTempFolderVideo();
    //         session()->flash("flash_sucesso", "Posição registrada com video para Google drive!");
    //         return redirect('/posicao');
    //     }else{

    //     }
    // }


    private function clearTempFolderVideo(){
        $diretorio = dir(public_path('/video_temp'));
        while($arquivo = $diretorio->read()){
            if(($arquivo != '.') && ($arquivo != '..'))
            {
                unlink(public_path('/video_temp/').$arquivo);
            }
        }
    }

    public function view($id){
        $user = session('user_logged');

        PosicaoView::create([
            'posicao_id' => $id,
            'aluno_id' => $user['aluno']->id
        ]);

        $item = Posicao::findOrFail($id);

        foreach($item->comentarios as $c){
            if($c->aluno_id == $user['aluno']->id && $c->resposta != ''){
                $c->resposta_view = 1;
                $c->save();
            }
        }
        return view('posicao/view', compact('item'));
    }

    public function newVideo(Request $request, $id){
        $item = Posicao::findOrFail($id);
        $video = $request->file('video_url');
        $videoName = "";
        if($video){
            $videoName = Str::random(20) . "." . $video->getClientOriginalExtension(); 
            $video->move(public_path('video_temp'), $videoName);

            $item->video_temp = $videoName;
            $item->save();
            $this->driveUpload();

        }
    }

    public function videoNewManual(Request $request, $id){
        $item = Posicao::findOrFail($id);
        PosicaoVideo::create([
            'posicao_id' => $item->id,
            'url' => $request->video_id,
            'tipo' => $request->tipo
        ]);
        session()->flash("flash_sucesso", "Video incluído manual!");
        return redirect()->back();
    }

    public function newComent(Request $request, $id){
        $item = Posicao::findOrFail($id);
        $user = session('user_logged');

        ComentarioVideo::create([
            'posicao_id' => $item->id,
            'aluno_id' => $user['aluno']->id,
            'comentario' => $request->comentario,
            'resposta' => ''
        ]);

        session()->flash("flash_sucesso", "Dúvida ou comentário incluído com sucesso!");
        return redirect()->back();

    }

    public function respostas(){
        $data = ComentarioVideo::
        where('resposta', '=', '')
        ->get();

        return view('posicao/respostas', compact('data'));
    }

    public function putComent(Request $request, $id){

        $item = ComentarioVideo::findOrFail($id);
        $item->resposta = $request->resposta;
        $item->save();
        session()->flash("flash_sucesso", "Dúvida ou comentário respondido com sucesso!");
        return redirect()->back();
    }

    public function push($id){

        $posicao = Posicao::findOrFail($id);
        $data = [
            'heading' => [
                "en" => 'Nova posição cadastrada'
            ],
            'content' => [
                "en" => $posicao->nome
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

        if($posicao->imagem != ""){
            $fields['chrome_web_image'] = getenv('APP_URL') . '/posicoes/' . $posicao->imagem;
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

        $obj = json_decode($response);

        session()->flash("flash_sucesso", "Push enviado para todos, ID AUTH: $obj->id");
        return redirect()->back();
    }

    public function respostaDestroy($id){
        $item = ComentarioVideo::findOrFail($id);
        try{
            $item->delete();
            session()->flash("flash_sucesso", "Registro removido!");

        }catch(\Exception $e){
            session()->flash("flash_erro", "Algo deu errado: " . $e->getMessage());

        }
        return redirect()->back();

    }
}
