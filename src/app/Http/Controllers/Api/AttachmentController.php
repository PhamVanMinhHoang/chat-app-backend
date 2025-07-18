<?php

namespace App\Http\Controllers\Api;

use App\Events\AttachmentAdded;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttachmentController extends Controller
{
    public function store(Request $request, Message $message)
    {
        try {
            DB::beginTransaction();
            $request->validate(['file'=>'required|file|max:20480']);
            $path = $request->file('file')->store('attachments','public');

            $url = "/storage/{$path}";
            $type = $request->file('file')->getClientMimeType();
            $name = $request->file('file')->getClientOriginalName();
            $size = $request->file('file')->getSize();
            $attachment = $message->attachments()->create([
                'file_url'=> $url,
                'file_type'=> $type,
                'file_name'=> $name,
                'file_size'=> $size,
            ]);
            broadcast(new AttachmentAdded($attachment, $message->conversation_id))->toOthers();

            DB::commit();

            return response()->json(
                [
                    'success'=>true,
                    'attachment'=>$attachment
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['success'=>false, 'message'=>$th->getMessage()], 500);
        }
    }
}
