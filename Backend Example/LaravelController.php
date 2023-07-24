<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Something;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SomethingTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $somethingsPaginated = Something::withTrashed();

        $somethingTableColumn = collect($request->somethingTableColumn ?? []);

        //filters
        foreach ($somethingTableColumn as $column) {
            if (filter_var($column['isFiltering'], FILTER_VALIDATE_BOOLEAN)) {
                if ($column['attributeName'] == 'email') {
                    $somethingsPaginated = $somethingsPaginated->where('email', 'LIKE', '%' . $column['filters']['email'] . '%');
                } else if ($column['attributeName'] == 'registeredAt') {
                    $somethingsPaginated = $somethingsPaginated->whereBetween('created_at', [Carbon::create($column['filters']['from'])->toISOString(), Carbon::create($column['filters']['to'])->addDay()->subSecond()->toISOString()]);
                } else if ($column['attributeName'] == 'unregisteredAt') {
                    $somethingsPaginated = $somethingsPaginated->whereBetween('deleted_at', [Carbon::create($column['filters']['from'])->toISOString(), Carbon::create($column['filters']['to'])->addDay()->subSecond()->toISOString()]);
                }
            }
        }
        
        
        //table column sort
            //sort by sortPriority and ignore column without sortPriority
            $sortedSomethingTableColumn = $somethingTableColumn->sortBy('sortPriority')->filter(function ($value, $key) {
                return isset($value['sortPriority']);
            });
            //add column to query based on sortPriority
            foreach ($sortedSomethingTableColumn as $column) {
                if ($column['attributeName'] == 'email') {
                    $somethingsPaginated = $somethingsPaginated->orderBy('email', $column['sortOrientation']);
                } else if ($column['attributeName'] == 'registeredAt') {
                    $somethingsPaginated = $somethingsPaginated->orderBy('created_at', $column['sortOrientation']);
                } else if ($column['attributeName'] == 'unregisteredAt') {
                    $somethingsPaginated = $somethingsPaginated->orderBy('deleted_at', $column['sortOrientation']);
                }
            }
        //
        
        $somethingsPaginated = $somethingsPaginated->paginate(10)->onEachSide(1);

        return [
            'somethingsPaginated' => $somethingsPaginated,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
