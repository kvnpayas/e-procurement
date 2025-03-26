import './bootstrap';

import 'flowbite';
import 'flowbite/dist/datepicker';

import { initFlowbite } from 'flowbite';

Livewire.hook('commit', ({ commit, succeed }) => {
  succeed(() => {
      queueMicrotask(() => {
          initFlowbite();
      });
  });
});

document.addEventListener('livewire:navigated', () => {
  initFlowbite();
})

