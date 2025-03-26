<?php
namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;

class SearchModel
{

  public static function search($model, $fields = [], $search)
  {
    if ($model instanceof Builder || $model instanceof Model) {
      return $model->where(function ($query) use ($search, $fields) {
        foreach ($fields as $field) {
          $query->orWhere($field, 'like', '%' . $search . '%');
        }
      });
    } elseif ($model instanceof Collection) {
      return $model->filter(function ($item) use ($search, $fields) {
        foreach ($fields as $field) {
          $fieldValue = $item[$field];

          if (stripos($fieldValue, $search) !== false) {
            return true;
          }
        }
        return false;
      });
    }
  }
}

