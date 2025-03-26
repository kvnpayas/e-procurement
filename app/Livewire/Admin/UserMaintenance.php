<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Helpers\SearchModel;
use Livewire\WithPagination;
use App\Helpers\LogsActivity;
use App\Mail\UserRegistration;
use App\Mail\UserResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class UserMaintenance extends Component
{
  use WithPagination;
  protected $userLists;
  public $name, $email, $address, $number, $role_id, $roles;
  public $editUserModel, $editName, $editEmail, $editAddress, $editNumber, $editRole_id, $editStatus, $switchStatus;
  public $search, $showAll = false;

  public function getUsers()
  {

    if ($this->showAll) {
      return User::whereNotIn('role_id', [1, 2])->orderBy('name', 'asc');
    } else {
      return User::whereNotIn('role_id', [1, 2])->where('active', true)->orderBy('name', 'asc');
    }
  }
  public function updatedSearch($search)
  {
    $this->resetPage();
    $fields = [
      'id',
      'name',
      'email',
      'address',
      'number',
    ];

    $model = $this->getUsers();
    if ($search) {
      $this->userLists = SearchModel::search($model, $fields, $search);
    } else {
      $this->userLists = $model;
    }
  }

  public function addUser()
  {
    $this->resetValidation();
    $this->dispatch('openAddUserModal');
  }
  public function closeAddUserModal()
  {
    $this->dispatch('closeAddUserModal');
  }

  public function userRegistration()
  {
    $this->name = ucwords($this->name);
    $token = Str::random(40);
    $data = $this->validate([
      'name' => 'required',
      'email' => 'required|email|unique:users,email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
      'address' => 'required',
      'number' => 'required',
      'role_id' => 'required',
    ]);
    $data['token'] = $token;
    $data['active'] = true;
    $data['crtd_user'] = Auth::user()->id;

    try {
      $user = User::create($data);
      LogsActivity::userMaintenance($user->email, 'Success!', 'Create User');
    } catch (ValidationException $e) {
      LogsActivity::userMaintenance($user->email, 'Failed: ' . $e->getMessage(), 'Create User');
    }
    try {
      Mail::to($user->email)->send(new UserRegistration($user));
      LogsActivity::emailNotification($user->email, 'Email sent successfully!', 'Create User');
    } catch (ValidationException $e) {
      LogsActivity::emailNotification($user->email, $e->getMessage(), 'Create User');
    }

    $this->dispatch('closeAddUserModal');
    $this->dispatch('success-message', ['message' => 'Successfully created! An email was sent to ' . $data['email'] . '.']);

    // return redirect()
    //   ->route('user-maintenance')
    //   ->with('success', 'Successfully created! An email was sent to ' . $data['email'] . '.');
  }

  public function editUser($id)
  {
    $this->editUserModel = $this->getUsers()->where('id', $id)->first();
    $this->editName = $this->editUserModel->name;
    $this->editEmail = $this->editUserModel->email;
    $this->editAddress = $this->editUserModel->address;
    $this->editNumber = $this->editUserModel->number;
    $this->editRole_id = $this->editUserModel->role_id;
    $this->editStatus = (bool) $this->editUserModel->active;
    $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
    $this->dispatch('openEditUserModal');
  }

  public function closeEditUserModal()
  {
    $this->dispatch('closeEditUserModal');
    $this->resetValidation();
  }
  public function updatedEditStatus()
  {
    $this->switchStatus = $this->editStatus ? 'Active' : 'Inactive';
  }
  public function eidtUserInfo()
  {
    $this->editName = ucwords($this->editName);
    $this->validate([
      'editName' => 'required',
      'editAddress' => 'required',
      'editNumber' => 'required',
      'editRole_id' => 'required',
    ]);

    $data = [
      'name' => $this->editName,
      'address' => $this->editAddress,
      'number' => $this->editNumber,
      'role_id' => $this->editRole_id,
      'active' => $this->editStatus,
    ];

    $this->editUserModel->update($data);

    $this->dispatch('closeEditUserModal');
    $this->dispatch('success-message', ['message' => 'Successfully updated!']);

    // return redirect()
    //   ->route('user-maintenance')
    //   ->with('success', 'Successfully updated!');
  }

  public function resetPassword()
  {
    $this->dispatch('openConfirmationModal');
    $this->dispatch('closeEditUserModal');
  }

  public function closeConfirmationModal()
  {
    $this->dispatch('closeConfirmationModal');
  }

  public function resetUserPassword()
  {
    // dd($this->editUserModel);
    $token = Str::random(40);
    $this->editUserModel->update([
      'password' => null,
      'token' => $token
    ]);
    try {
      Mail::to($this->editUserModel->email)->send(new UserResetPassword($this->editUserModel, $token));
      LogsActivity::emailNotification($this->editUserModel->email, 'Email sent successfully!', 'Password Reset');
    } catch (ValidationException $e) {
      LogsActivity::emailNotification($this->editUserModel->email, $e->getMessage(), 'Password Reset');
    }
    // return redirect()
    //   ->route('user-maintenance')
    //   ->with('success', 'Reset password success! An email was sent to ' . $this->editEmail . '.');
    $this->userLists = $this->getUsers();
    $this->dispatch('closeConfirmationModal');
    $this->dispatch('success-message', ['message' => 'Reset password success! An email was sent to ' . $this->editEmail . '.']);
  }

  public function render()
  {
    if (!$this->userLists) {
      $this->userLists = $this->getUsers();
    }

    $this->roles = Role::whereNotIn('role_name', ['Admin', 'Vendor'])->get();
    return view('livewire.admin.user-maintenance', [
      'users' => $this->userLists->paginate(10)
    ]);
  }
}
