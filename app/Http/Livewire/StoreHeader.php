<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;
use App\Models\Category;

class StoreHeader extends Component
{
  public $session_id;
  protected $listeners = [
    'newcart' => 'NewCart',
    'orderprocess' => 'getCartProperty',
  ];

  private function getSessionId()
  {
    if (array_key_exists('sessionId', $_COOKIE)) {
      return $_COOKIE['sessionId'];
    } else {
      $sessionId = session()->getId();
      setcookie('sessionId', $sessionId, time() + 30 * 24 * 60 * 60, '/', null, false, true);
      return $sessionId;
    }
  }

  public function render()
  {
    $data = [
      'categories' => $this->categories,
      'cart' => $this->cart,

    ];
    return view('livewire.store-header', $data);
  }
  public function mount()
  {
    $this->session_id = $this->getSessionId();
  }

  public function getCartProperty()
  {
    return Cart::select('id', 'quantity_amount')
      ->where('session_id', $this->session_id)
      ->where('status_id', '!=', app('global_cart_closed'))
      ->latest()
      ->first() ?? null;
  }

  public function NewCart()
  {
    $this->getCategoriesProperty();
    $this->emit('newcartlist');
  }

  public function getCategoriesProperty()
  {
    return Category::select('id', 'name', 'seo_id', 'sequence')
      ->with([
        'media' => function ($query) {
          $query->where('type', 'min')->select('media_id', 'path', 'name');
        },
        'subcategory' => function ($query) {
          $query->whereHas('category', function ($query) {
            $this->applyCategoryConditions($query);
          })->with([
            'category' => function ($query) {
              $query->select('id', 'name', 'seo_id', 'sequence');
              $this->applyCategoryConditions($query);
              $query->with([
                'media' => function ($query) {
                  $query->where('type', 'min')->select('media_id', 'path', 'name');
                },
                'subcategory' => function ($query) {
                  $query->whereHas('category', function ($query) {
                    $this->applyCategoryConditions($query);
                  })->with([
                    'category' => function ($query) {
                      $query->select('id', 'name', 'seo_id', 'sequence');
                      $this->applyCategoryConditions($query);
                      $query->with([
                        'media' => function ($query) {
                          $query->where('type', 'min')->select('media_id', 'path', 'name');
                        }
                      ]);
                    }
                  ]);
                }
              ]);
            }
          ]);
        }
      ])
      ->where('active', 1)
      ->where('store_tab', 1)
      ->where('has_parrent', 0)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->orderBy('sequence')
      ->limit(app('global_limit_category'))->get();
  }

  protected function applyCategoryConditions($query)
  {
    $query->where('active', 1)
      ->where('store_tab', 1)
      ->where('start_date', '<=', now()->format('Y-m-d'))
      ->where('end_date', '>=', now()->format('Y-m-d'))
      ->orderBy('sequence');
  }
}
