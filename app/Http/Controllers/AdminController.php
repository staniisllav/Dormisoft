<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomScript;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;




class AdminController extends Controller
{

  function correctMediaSequence()
  {
    // Select original and full media entries with duplicate paths
    $mediaGroups = DB::table('media')
      ->select('path')
      ->groupBy('path')
      ->get();

    foreach ($mediaGroups as $mediaGroup) {
      // Get the associated media entries
      $mediaEntries = DB::table('media')
        ->where('path', $mediaGroup->path)
        ->where(
          'sequence',
          '!=',
          1
        )
        ->get();
      $index = 2;

      foreach ($mediaEntries as $item) {
        // Update the sequence and name for each media entry
        DB::table('media')
          ->where('id', $item->id)
          ->where('type', 'original')
          ->update([
            'sequence' => $index,
          ]);
        DB::table('media')
          ->where('id', $item->id)
          ->where('type', 'full')
          ->update([
            'sequence' => $index - 1,
          ]);
        $index++;
      }
    }
    return true;
  }
  //admin function

  public function storesettings()
  {
    return view('admin.store_settings');
  }
  public function customscripts()
  {
    return view('admin.customscripts');
  }

  public function accounts()
  {
    return view('admin.accounts');
  }
  public function show_account($id)
  {
    $data = Account::find($id);
    return view('admin.show_account', compact('data'));
  }
  public function show_script($id)
  {
    $data = CustomScript::find($id);
    return view('admin.show_script', compact('data'));
  }
  public function vouchers()
  {
    return view('admin.voucher');
  }
  public function payments()
  {
    return view('admin.payment');
  }

  public function create_voucher()
  {
    return view('admin.add_voucher');
  }

  public function create_script()
  {
    return view('admin.add_script');
  }

  public function store_script(Request $request)
  {
    if (!$request->filled('content')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please provide the specific script!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    if (!$request->filled('name')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'Please provide the script name!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }

    CustomScript::create([
      'name' => $request->name,
      'type' => $request->type,
      'content' => $request->content,
      'active' => $request->has('active')
    ]);
    Cache::forget('global_scripts');

    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }

  public function store_voucher(Request $request)
  {
    // Validation rules
    $rules = [
      'start_date' => 'required|date|after_or_equal:today',
      'end_date' => 'required|date|after_or_equal:start_date',
      'percent' => [
        'nullable',
        'integer',
        'between:1,100',
      ],
      'value' => [
        'nullable',
        'gt:0'
      ]
    ];
    // Custom validation messages
    $messages = [
      'start_date.after_or_equal' => 'The start date must be in the future or present.',
      'end_date.after_or_equal' => 'The end date must be in the future and after the start date.',
      'percent' => 'The percent must be between 1-100',
      'value' => 'The value must be bigger than 0'

    ];
    $rules['percent_or_value'] = 'required_without_all:percent,value';

    $this->validate(
      $request,
      $rules,
      $messages
    );

    // If neither percent nor value is provided, redirect back with an error
    if ($request->filled('percent') && $request->filled('value')) {
      return redirect()->back()->withInput()->with([
        'notification' => [
          'message' => 'The voucher accepts either a percent or a value, not both!',
          'type' => 'error',
          'title' => 'Something went wrong'
        ],
      ]);
    }
    $voucher = new Voucher();
    $voucher->name = $request->name;
    $voucher->code = $request->code;
    $voucher->percent = $request->percent;
    $voucher->value = $request->value;
    $voucher->status_id = app('global_voucher_active');
    $voucher->start_date = $request->start_date;
    $voucher->end_date = $request->end_date;
    $voucher->single_use = $request->has('single_use');
    $voucher->save();

    return redirect()->back()->with([
      'notification' => [
        'message' => 'Record added successfully!',
        'type' => 'success',
        'title' => 'Success'
      ],
    ]);
  }



  public function addstoresetting()
  {
    return view('admin.add_storesetting');
  }

  public function orders()
  {
    return view('admin.order');
  }
  public function show_order($id)
  {
    $data = Order::find($id);
    return view('admin.show_order', compact('data'));
  }
}
