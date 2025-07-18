<?php

namespace App\Http\Controllers\Api;

use App\Events\ReactionRemoved;
use App\Events\ReactionUpdated;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Reaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ReactionController extends Controller
{
    public function store(Request $request, Message $message): JsonResponse
    {
        try {
            $data = $request->validate(
                [
                    'reaction_type'=>'required|string'
                ]
            );
            $reaction = Reaction::updateOrCreate(
                ['message_id'=>$message->id,'user_id'=>$request->user()->id],
                ['reaction_type'=>$data['reaction_type']]
            );
            broadcast(new ReactionUpdated($reaction, $message->conversation_id))->toOthers();
            return response()->json(['success'=>true]);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false, 'message'=>$th->getMessage()], 500);
        }
    }

    public function destroy(Request $request, Message $message): JsonResponse
    {
        try {
            Reaction::where('message_id',$message->id)
                ->where('user_id',$request->user()->id)
                ->delete();
            broadcast(new ReactionRemoved($message->id, $request->user()->id, $message->conversation_id))->toOthers();
            return response()->json(['success'=>true], ResponseAlias::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response()->json(['success'=>false, 'message'=>$th->getMessage()], 500);
        }
    }
}
