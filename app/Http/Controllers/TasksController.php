<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //getでtasks/にアクセスされた場合の「一覧表示処理」
        $data =[];
        if(\Auth::check()) { 
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'asc')->paginate(10);
             
            $data = [
                 'user' => $user,
                 'tasks' => $tasks,
                 ];
        }
         // トップページへリダイレクトさせる
            return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
        
        $task = new Task;
        
        return view("tasks.create",[
            "task" => $task,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            "status" => "required|max:10",
            "content" => "required",
            ]);
        
        //postでtasks/にアクセスされた場合の「新規登録処理」
        $task = new Task;
        // TODO: ログインしているユーザーのIDを$task->user_idにいれる
        // 上記は\Auth::id()で取得可能
        $task->user_id = \Auth::id(); //登録したユーザーのidを引っ張ってくる
        $task->status = $request->status;   //statusの追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //getでtasks/(任意のid)にアクセスされた場合の「取得表示処理」
        $task = Task::findOrFail($id);
         if (\Auth::id() === $task->user_id) {
         return view('tasks.show', [
            'task' => $task,
         ]);
         }
         return redirect('tasks.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //getでtasks/(任意のid)/editにアクセスされた場合の「更新画面表示処理」
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
        //タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
        }
        return redirect('tasks.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //バリデーション
        $request->validate([
            "status" => "required|max:10",
            "content" => "required",
            ]);
        
        //putまたはpatchでtasks/(任意のid)にアクセスされた場合の「更新処理」
         // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        if (\Auth::id() === $task->user_id) {
        // タスクを更新
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();
        }
        // トップページへリダイレクトさせる
        return redirect('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //deleteでtasks/(任意のid)にアクセスされた場合の「削除処理」
         // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // メッセージを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        // トップページへリダイレクトさせる
        return redirect('tasks.index');
    }
}
