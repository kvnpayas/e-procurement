<?php

use Livewire\Volt\Component;

new class extends Component {
    public $user;

    public function mount($user): void
    {
        $this->user = $user;
    }
};
?>
<div>
  <livewire:pages.auth.vendor-register :user='$user' />
</div>
