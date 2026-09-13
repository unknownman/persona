import { readonly, ref, type Ref } from 'vue';
import axios, { type AxiosError, type AxiosInstance } from 'axios';
import { useForm, type Form } from '@inertiajs/vue3';
import type {
  Contact,
  CreateContactInput,
  PersonaId,
  Profile,
} from '../types/persona';

/** The polymorphic owner every Persona payload is scoped to. */
export interface PersonableScope {
  personableType: string;
  personableId: PersonaId;
}

export interface UsePersonaOptions {
  /** Base URL for the Persona API, e.g. "/api/persona". Defaults to "/api/persona". */
  baseURL?: string;
  /** Pre-configured Axios instance used for reads (honored over baseURL). */
  http?: AxiosInstance;
}

export interface UsePersonaReturn {
  loading: Readonly<Ref<boolean>>;
  error: Readonly<Ref<string | null>>;
  success: Readonly<Ref<string | null>>;
  /** Live contact list, refreshed after every mutation. */
  contacts: Readonly<Ref<Contact[]>>;
  /** Inertia form bound to the "create contact" mutation. */
  contactForm: Form<CreateContactInput>;
  fetchProfile: () => Promise<Profile | null>;
  fetchContacts: () => Promise<Contact[]>;
  /** Submit the create-contact form through Inertia. */
  addContact: () => void;
  /** Delete a contact through an Inertia form request. */
  removeContact: (contactId: PersonaId) => void;
  /** Promote a contact to primary through an Inertia form request. */
  setPrimaryContact: (contactId: PersonaId) => void;
  clearSuccess: () => void;
}

/**
 * Vue 3 Composition API composable for interacting with the headless
 * Persona backend.
 *
 * Reads are performed over HTTP (GET), while every mutation runs through
 * Inertia form helpers (useForm) so validation errors, CSRF handling, and
 * pending states are handled the Inertia-native way.
 */
export function usePersona(
  scope: PersonableScope,
  options: UsePersonaOptions = {},
): UsePersonaReturn {
  const { personableType, personableId } = scope;

  const http: AxiosInstance =
    options.http ?? axios.create({ baseURL: options.baseURL ?? '/api/persona' });

  const params = { personable_type: personableType, personable_id: personableId };

  const loading = ref(false);
  const error = ref<string | null>(null);
  const success = ref<string | null>(null);
  const contacts = ref<Contact[]>([]);

  const contactForm = useForm<CreateContactInput>({
    type: 'email',
    value: '',
    isPrimary: false,
    isEmergency: false,
  });

  const removeForm = useForm<Record<string, never>>({});
  const primaryForm = useForm<Record<string, never>>({});

  async function run<T>(operation: () => Promise<T>): Promise<T> {
    loading.value = true;
    error.value = null;
    try {
      return await operation();
    } catch (cause) {
      const axiosError = cause as AxiosError<{ message?: string }>;
      error.value =
        axiosError.response?.data?.message ??
        axiosError.message ??
        'Something went wrong while talking to the Persona API.';
      throw cause;
    } finally {
      loading.value = false;
    }
  }

  async function fetchProfile(): Promise<Profile | null> {
    return run(async () => {
      try {
        const { data } = await http.get<Profile>('/profile', { params });
        return data;
      } catch (cause) {
        const axiosError = cause as AxiosError;
        if (axiosError.response?.status === 404) {
          return null;
        }
        throw cause;
      }
    });
  }

  async function fetchContacts(): Promise<Contact[]> {
    return run(async () => {
      const { data } = await http.get<Contact[]>('/contacts', { params });
      contacts.value = data;
      return data;
    });
  }

  function addContact(): void {
    error.value = null;
    success.value = null;

    contactForm.post('/contacts', {
      onSuccess: () => {
        success.value = `${contactForm.data.value} added as a ${contactForm.data.type} contact.`;
        contactForm.reset();
        void fetchContacts();
      },
    });
  }

  function removeContact(contactId: PersonaId): void {
    error.value = null;
    success.value = null;

    removeForm.delete(`/contacts/${contactId}`, {
      onSuccess: () => {
        success.value = 'Contact removed.';
        void fetchContacts();
      },
    });
  }

  function setPrimaryContact(contactId: PersonaId): void {
    error.value = null;
    success.value = null;

    primaryForm.patch(`/contacts/${contactId}/primary`, {
      onSuccess: () => {
        success.value = 'Primary contact updated.';
        void fetchContacts();
      },
    });
  }

  function clearSuccess(): void {
    success.value = null;
  }

  return {
    loading: readonly(loading),
    error: readonly(error),
    success: readonly(success),
    contacts: readonly(contacts),
    contactForm,
    fetchProfile,
    fetchContacts,
    addContact,
    removeContact,
    setPrimaryContact,
    clearSuccess,
  };
}