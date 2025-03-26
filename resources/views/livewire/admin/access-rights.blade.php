<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
  <table class="w-full text-sm text-left rtl:text-right text-gray-500">
    <caption class="p-5 text-lg font-semibold text-left rtl:text-right tei-text-secondary bg-white ">
      Access Rights
      <p class="mt-1 text-sm font-normal text-gray-500 ">Assign menus or access to the admin roles.
      </p>
    </caption>
    <thead class="text-xs text-gray-700 uppercase tei-bg-light ">
      <tr>
        <th scope="col" class="px-6 py-3">
          Role Id
        </th>
        <th scope="col" class="px-6 py-3">
          Role Name
        </th>
        <th scope="col" class="px-6 py-3">
          # of users
        </th>
        <th scope="col" class="px-6 py-3">
          # of module access
        </th>
        <th scope="col" class="px-6 py-3">
          Action
        </th>
      </tr>
    </thead>
    <tbody>
      @forelse ($roles as $role)
        <tr class="bg-white border-b font-semibold">
          <th scope="row" class="px-6 py-2 tei-text-secondary text-gray-900 whitespace-nowrap ">
            {{ $role->id }}
          </th>
          <th scope="row" class="px-6 py-2 tei-text-primary whitespace-nowrap ">
            {{ $role->role_name }}
          </th>
          <td class="px-6 py-2">
            {{ $role->users->count() }}
          </td>
          <td class="px-6 py-2">
            {{ $role->menus->count() }}
          </td>
          <td class="px-6 py-2">
            <button wire:click.prevent="menuModal({{ $role->id }})" wire:loading.remove wire:target="menuModal({{ $role->id }})"
              class="hover:scale-110 transition-transform duration-300 mr-4 tei-bg-primary rounded-md px-2 py-1 text-white text-xs">Add/Remove</button>
              <x-loading-spinner color="var(--secondary)" target="menuModal({{ $role->id }})" />
          </td>
        </tr>
      @empty
        <tr class="bg-white border-b">
          <th scope="row" colspan="100" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap text-center">
            <span class="font-black text-lg tei-text-primary">No roles.</span>
          </th>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Menu Modal --}}
  <div id="menu-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" wire:ignore.self
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
      <!-- Modal content -->
      <div class="relative bg-white rounded-lg shadow">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
          <h3 class="text-xl font-semibold tei-text-secondary">
            Add/Remove Access Rights for <span class="tei-text-primary">{{ $roleName }}</span>
          </h3>
          <button type="button"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
            wire:click="closeMenuModal">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 14 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
            </svg>
            <span class="sr-only">Close modal</span>
          </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5 space-y-4">
          <div>
            <p class="mt-1 text-sm font-normal text-gray-500 ">Manage role permissions by adding or removing module.</p>
            <div class="mb-6 w-full">
              {{-- @forelse ($modules as $module)
                <div class="mt-4">
                  <h3 class="tei-bg-primary tei-text-light uppercase p-1.5 font-semibold ">{{ $module }}</h3>
                  @foreach ($menus as $menu)
                    @if ($menu->module == $module)
                      <div
                        class="grid grid-cols-3 gap-4 p-1.5 hover:bg-neutral-300 hover:shadow-xl transition duration-300">
                        <div>
                          <span class="uppercase text-xs tei-text-secondary font-semibold">{{ $menu->name }}</span>
                        </div>
                        <div>
                          <span class="text-xs tei-text-accent font-semibold">{{ $menu->route_name }}</span>
                        </div>
                        <div class="flex justify-center">
                          <div class="flex items-center me-4">
                            <input wire:model="checkMenus.{{ $menu->id }}" value="{{ $menu->id }}"
                              type="checkbox"
                              class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach

                </div>
              @empty
                No Module
              @endforelse --}}
              @forelse ($modules as $module)
                <div class="mt-4">
                  <table class="w-full">
                    <thead>
                      <td colspan="7">
                        <h3 class="tei-bg-primary tei-text-light uppercase p-1.5 font-semibold ">{{ $module }}
                        </h3>
                      </td>
                    </thead>
                    <thead class="tei-bg-light text-xs uppercase font-extrabold tei-text-accent">
                      <td class="px-2">Menu Name</td>
                      {{-- <td class="px-2">Route Name</td> --}}
                      <th class="px-2">Add/Remove Module</th>
                      <th class="px-2">View</th>
                      <th class="px-2">Create</th>
                      <th class="px-2">Update</th>
                      <th class="px-2">Review</th>
                    </thead>
                    <tbody>
                      {{-- <h3 class="tei-bg-primary tei-text-light uppercase p-1.5 font-semibold ">{{ $module }}</h3> --}}
                      @foreach ($menus as $menu)
                        @if ($menu->module == $module)
                          <tr>
                            <td class="px-2">
                              <span
                                class="uppercase text-xs tei-text-secondary font-semibold">{{ $menu->name }}</span>
                            </td>
                            {{-- <td class="px-2">
                              <span class="text-xs tei-text-accent font-semibold">{{ $menu->route_name }}</span>
                            </td> --}}
                            <td class="px-2">
                              <div class="flex justify-center">
                                <div class="flex items-center">
                                  <input wire:model="checkMenus.{{ $menu->id }}" wire:click="checkMenusValue({{$menu->id}})"
                                    type="checkbox"
                                    class="w-4 h-4 text-green-600 bg-white border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                                </div>
                              </div>
                            </td>
                            <td class="px-2">
                              <div class="flex justify-center">
                                <div class="flex items-center">
                                  <input wire:model="accessRights.{{ $menu->id }}.view" value="{{ $menu->id }}"
                                    type="checkbox" {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? '' : 'disabled' }}
                                    class="w-4 h-4 text-green-600  rounded focus:ring-green-500 focus:ring-2  {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? 'bg-white border-gray-300' : 'bg-gray-200 border-gray-300' }}">
                                </div>
                              </div>
                            </td>
                            <td class="px-2">
                              <div class="flex justify-center">
                                <div class="flex items-center">
                                  <input wire:model="accessRights.{{ $menu->id }}.create" value="{{ $menu->id }}"
                                    type="checkbox" {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? '' : 'disabled' }}
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? 'bg-white border-gray-300' : 'bg-gray-200 border-gray-300' }}">
                                </div>
                              </div>
                            </td>
                            <td class="px-2">
                              <div class="flex justify-center">
                                <div class="flex items-center">
                                  <input wire:model="accessRights.{{ $menu->id }}.update" value="{{ $menu->id }}"
                                    type="checkbox" {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? '' : 'disabled' }}
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? 'bg-white border-gray-300' : 'bg-gray-200 border-gray-300' }}">
                                </div>
                              </div>
                            </td>
                            <td class="px-2">
                              <div class="flex justify-center">
                                <div class="flex items-center">
                                  <input wire:model="accessRights.{{ $menu->id }}.review" value="{{ $menu->id }}"
                                    type="checkbox" {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? '' : 'disabled' }}
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2 {{ isset($accessRights[$menu->id]) && $accessRights[$menu->id]['menu_check'] ? 'bg-white border-gray-300' : 'bg-gray-200 border-gray-300' }}">
                                </div>
                              </div>
                            </td>
                          </tr>
                          {{-- <div
                        class="grid grid-cols-3 gap-4 p-1.5 hover:bg-neutral-300 hover:shadow-xl transition duration-300">
                        <div>
                          <span class="uppercase text-xs tei-text-secondary font-semibold">{{ $menu->name }}</span>
                        </div>
                        <div>
                          <span class="text-xs tei-text-accent font-semibold">{{ $menu->route_name }}</span>
                        </div>
                        <div class="flex justify-center">
                          <div class="flex items-center me-4">
                            <input wire:model="checkMenus.{{ $menu->id }}" value="{{ $menu->id }}"
                              type="checkbox"
                              class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                          </div>
                        </div>
                      </div> --}}
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @empty
                No Module
              @endforelse

            </div>
          </div>
          {{-- <div>
            <p class="mt-1 text-sm font-normal text-gray-500 ">Select role permissions by adding or removing access
              rights.</p>
            <div class="mt-4">
              <h3 class="tei-bg-primary tei-text-light uppercase p-1.5 font-semibold ">Role Access Rights</h3>
              <div class="grid grid-cols-4 mt-5 px-2">
                <div class="flex gap-5">
                  <span class="text-xs uppercase tei-text-accent font-black">View</span>
                  <input wire:model="roleAccess.view" type="checkbox"
                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                </div>
                <div class="flex gap-5">
                  <span class="text-xs uppercase tei-text-accent font-black">Create</span>
                  <input wire:model="roleAccess.create" type="checkbox"
                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                </div>
                <div class="flex gap-5">
                  <span class="text-xs uppercase tei-text-accent font-black">Update</span>
                  <input wire:model="roleAccess.update" type="checkbox"
                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                </div>
                <div class="flex gap-5">
                  <span class="text-xs uppercase tei-text-accent font-black">Review</span>
                  <input wire:model="roleAccess.review" type="checkbox"
                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                </div>
              </div>
            </div>
          </div> --}}
        </div>
        <!-- Modal footer -->
        <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b">
          <button type="submit" wire:click="updateMenus" wire:loading.remove wire:target="updateMenus"
            class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Update</button>
          <div class="w-20 rounded-lg tei-bg-light flex justify-center p-4 md:p-5" wire:loading
            wire:target="updateMenus">
            <div class="flex justify-center ">
              <div class="loading-small loading-main">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </div>
          <button wire:click="closeMenuModal" type="button"
            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10">Close</button>
        </div>
      </div>
    </div>
  </div>
  {{-- Menu Modal --}}

</div>
@script
  <script>
    $wire.on('openMenuModal', () => {
      var modalElement = document.getElementById('menu-modal');
      var modal = new Modal(modalElement, {
        backdrop: 'static'
      });
      modal.show();
    });
    $wire.on('closeMenuModal', () => {
      var modalElement = document.getElementById('menu-modal');
      var modal = new Modal(modalElement);
      modal.hide();
    });
  </script>
@endscript
