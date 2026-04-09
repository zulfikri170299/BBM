import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';
import * as Turbo from '@hotwired/turbo';

Alpine.plugin(collapse);
Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.start();

// Configure Turbo
Turbo.config.forms.mode = "optin";
