<?php

namespace App\View\Components;

use App\Models\UserRolePermission;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class AdminPermissionPicker extends Component
{
    public $current;
    //    public $current_categories;
    public $firstLevel;
    public $name;
    public $exclude;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $name = 'allows[]', $current = null)
    {
        //
        $this->current = $current;
        //        if($current){
        //            $this->current_categories = $current->get();
        //        }
        //        else {
        //            $this->current_categories = new Collection();
        //        }

        $this->name = $name;
        $this->exclude = [];

        //$this->categories = Category::all();
        $this->firstLevel = UserRolePermission::doesntHave('parent')->get();

    }

    public function renderFirstLevel($pop, int $level, Collection $items)
    {

        $items_render = $items->map(
            function (UserRolePermission $permission) use ($level, $pop) {
                $name = $permission->getTextName();

                $childrenCount = $permission->children()->count();
                $childrenHTML = '';
                //
                //            if($childrenCount > 0) {
                //                $childrenHTML = '<span class="total">(' . $childrenCount . ')</span>';
                //            }

                $inputHTML = '<input type="checkbox" id="permission' . $permission->id . '" name="' . $this->name . '" value="' . $permission->key_name . '" ' . ($this->isSelected($permission) ? 'checked' : '') . '>';


                $isPickable = true;


                $children = '<ul>' . $this->renderLevel($permission->id, $level + 1, $permission->children()->whereNotIn('id', $this->exclude)->get()) . '</ul>';

                return '<div class="col-12 col-md-6 col-lg-4 role-permission-picker">
    <div class="role-permission-picker-head ' . ($isPickable ? 'can-select' : '') . '">
        ' . ($isPickable ? $inputHTML : '') . '
        <label for="permission' . $permission->id . '">' . ($isPickable ? '' : '<i class="mdi mdi-plus"></i> ') . '' . $this->getTextHtml($permission) . '</label>
    </div>
    ' . $children . '
  </div>';

            }
        )->toArray();


        return implode("\n", $items_render);
    }

    public function isSelected(UserRolePermission $option): bool
    {
        if (!$this->current) {
            return false;
        }

        return in_array($option->key_name, $this->current);
    }

    public function renderLevel($pop, int $level, Collection $items)
    {

        $items_render = $items->map(
            function (UserRolePermission $permission) use ($level, $pop) {
                $name = $permission->getTextName();

                $childrenCount = $permission->children()->count();
                $childrenHTML = '';

                if ($childrenCount > 0) {
                    $childrenHTML = '<span class="total">(' . $childrenCount . ')</span>';
                }

                $inputHTML = '<input type="checkbox" id="permission' . $permission->id . '" name="' . $this->name . '" value="' . $permission->key_name . '" ' . ($this->isSelected($permission) ? 'checked' : '') . '>';


                $isPickable = true;


                $children = '<ul>' . $this->renderLevel($permission->id, $level + 1, $permission->children()->whereNotIn('id', $this->exclude)->get()) . '</ul>';

                return '<li class="has level-' . $level . '">
    <div class="role-permission-picker-head ' . ($isPickable ? 'can-select' : '') . '">
        ' . ($isPickable ? $inputHTML : '') . '
        <label for="permission' . $permission->id . '">' . ($isPickable ? '' : '<i class="mdi mdi-plus"></i> ') . '' . $this->getTextHtml($permission) . '</label>
    </div>
    ' . $children . '
  </li>';

            }
        )->toArray();


        return implode("\n", $items_render);
    }

    public function getTextHtml(UserRolePermission $option)
    {
        $additional = '';

        if ($option->description) {
            $additional = '<p>' . $option->description . '</p>';
        }

        return "<div><h5>" . $option->getTextName() . "</h5>" . $additional . "</div>";
    }

    public function isRequired(): bool
    {
        return true;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.admin-permission-picker');
    }
}
