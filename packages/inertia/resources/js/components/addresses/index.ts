/**
 * Address mutation components - public surface.
 *
 * Compose through `PersonaAddressManager` for the full add/primary/delete
 * workflow, or use the individual atoms when you only need a subset:
 *
 *     import { PersonaAddressManager, AddressForm } from '../components/addresses';
 */
export { default as PersonaAddressManager } from './PersonaAddressManager.vue';
export { default as AddressForm } from './AddressForm.vue';
export { default as AddressItem } from './AddressItem.vue';
export { default as AddressList } from './AddressList.vue';