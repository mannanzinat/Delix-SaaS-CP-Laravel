<?php

namespace App\Repositories\Client;

use App\Models\Flow;
use App\Models\FlowBuilderFile;
use Illuminate\Support\Facades\File;

class FlowBuilderRepository
{
    public function all($with = [])
    {
        return Flow::latest()->paginate(setting('paginate'));
    }

    public function find($id)
    {
        return Flow::find($id);
    }

    public function store($data)
    {
        $flow_data = [
            'name'      => $data['name'],
            'client_id' => auth()->user()->client_id,
            'data'      => $data['flow_data'],
            'status'    => arrayCheck('status', $data),
        ];
        $flow      = Flow::create($flow_data);
        $this->parseRequestData($flow, $data['flow_data']);

        return Flow::create($flow_data);
    }

    public function update($data, $id)
    {
        $flow      = $this->find($id);
        $flow_data = [
            'name' => $data['name'],
            'data' => $data['flow_data'],
        ];
        $flow->update($flow_data);
        $flow->nodes()->delete();
        $flow->edges()->delete();
        $this->parseRequestData($flow, $data['flow_data']);

        return $flow;
    }

    private function parseRequestData($flow, $flow_data): void
    {
        $nodes    = $edges = [];
        $messages = collect($flow_data['messages']);
        foreach ($flow_data['elements']['nodes'] as $node_data) {
            $data    = [];
            $box     = $messages->where('id', $node_data['id'])->first();
            if ($node_data['type'] == 'box-with-title') {
                $data['text']     = $box['text'];
                $data['duration'] = getArrayValue('text_duration', $box, 0);
            } elseif ($node_data['type'] == 'node-image') {
                $data['image']    = $box['image'];
                $data['duration'] = getArrayValue('image_duration', $box, 0);
            } elseif ($node_data['type'] == 'box-with-audio') {
                $data['audio']    = $box['audio'];
                $data['duration'] = getArrayValue('audio_duration', $box, 0);
            } elseif ($node_data['type'] == 'box-with-video') {
                $data['video']    = $box['video'];
                $data['duration'] = getArrayValue('video_duration', $box, 0);
            } elseif ($node_data['type'] == 'box-with-file') {
                $data['file']     = $box['file'];
                $data['duration'] = getArrayValue('file_duration', $box, 0);
            } elseif ($node_data['type'] == 'box-with-location') {
                $data['lat']      = $box['latitude'];
                $data['long']     = $box['longitude'];
                $data['duration'] = getArrayValue('location_duration', $box, 0);
            } elseif ($node_data['type'] == 'box-with-condition') {
                $data['conditions'] = getArrayValue('condition_fields', $box, []);
            }

            $nodes[] = [
                'node_id'  => $node_data['id'],
                'type'     => $node_data['type'],
                'position' => $node_data['position'],
                'data'     => $data,
            ];
        }
        $flow->nodes()->createMany($nodes);
        foreach ($flow_data['elements']['edges'] as $edge_data) {
            $edges[] = [
                'edge_id'      => $edge_data['id'],
                'source'       => $edge_data['source'],
                'target'       => $edge_data['target'],
                'data'         => $edge_data['data'],
                'sourceHandle' => $edge_data['sourceHandle'],
            ];
        }
        $flow->edges()->createMany($edges);
    }

    public function statusChange($request)
    {
        $id = $request['id'];

        return $this->find($id)->update(['status' => $request['data']['value']]);
    }

    public function destroy($id): bool
    {
        $flow     = $this->find($id);
        $nodes_id = $flow->nodes->pluck('node_id');
        $flies    = FlowBuilderFile::whereIn('flow_template_id', $nodes_id->toArray())->get();

        foreach ($flies as $file) {
            File::delete('public/'.$file->file);
            $file->delete();
        }
        $flow->nodes()->delete();
        $flow->edges()->delete();
        $flow->delete();

        return true;
    }
}
