<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index(Request $request, Topic $topic)
    {
        $topics = $topic->withOrder($request->order)->paginate(20);
//        $topics = Topic::with('user', 'category')->paginate(30);
        return view('topics.index', compact('topics'));
    }

    public function show(Topic $topic)
    {
        return view('topics.show', compact('topic'));
    }

    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = \Auth::id();
        $topic->save();

        return redirect()->route('topics.show', $topic->id)->with('success', '帖子創建成功！');
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();

        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->route('topics.show', $topic->id)->with('message', 'Updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除！');
    }

    public function uploadImage(Request $request, ImageUploadHandler $imageUploadHandler)
    {
        // 初始化返回數據，預設是失败的
        $data = (object)[
            'success'   => false,
            'msg'       => '上傳失敗!',
            'file_path' => ''
        ];

        // 判斷有沒有值，並賦值给 $file
        if ($file = $request->upload_file) {
            $result = $imageUploadHandler->save($file, 'topics', \Auth::id(), 1024);

            //圖片保存成功的話
            if ($result) {
                $data->success   = true;
                $data->msg       = '上傳成功！';
                $data->file_path = $result['path'];
            }
        }
        return response()->json($data);

    }
}