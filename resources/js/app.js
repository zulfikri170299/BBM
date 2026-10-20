import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import persist from '@alpinejs/persist';
import * as Turbo from '@hotwired/turbo';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

flatpickr.localize(Indonesian);
window.flatpickr = flatpickr;

Alpine.plugin(collapse);
Alpine.plugin(persist);

window.Alpine = Alpine;

Alpine.start();

// Configure Turbo
Turbo.config.forms.mode = "optin";
