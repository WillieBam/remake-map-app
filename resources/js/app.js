import './bootstrap';

import Alpine from 'alpinejs';
import * as maptilersdk from '@maptiler/sdk';
import '@maptiler/sdk/dist/maptiler-sdk.css';

window.Alpine = Alpine;
window.maptilersdk = maptilersdk;

Alpine.start();
