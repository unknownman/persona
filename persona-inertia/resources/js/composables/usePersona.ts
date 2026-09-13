import { ref, readonly, type Ref } from 'vue';
import axios, { type AxiosInstance, type AxiosError } from 'axios';
import type {
  Contact,
  CreateContactInput,
  Profile,
} from '../types/persona';

export interface UsePersonaOptions {
  /** Base URL for the Persona API, e.g. "/api/persona". Defaults to "/api/persona". */
  baseURL?: string;
  /** Pre-configured Axios instance (honored over baseURL). */
  http?: AxiosInstance;
}

export interface UsePersonaReturn {
  loading: Ref<boolean>;
  error: Readonly<Ref<string | null>>;
  fetchProfile: (personableType: string, personableId: number) => Promise<Profile>;
  fetchContacts: (personableType: string, personableId: number) => Promise<Contact[]>;
  addContact: (
    personableType: string,
    personableId: number,
    input: CreateContactInput,
  ) => Promise<Contact>;
  removeContact: (contactId: number) => Promise<void>;
  setPrimaryContact: (contactId: number) => Promise<void>;
}

/**
 * Vue 3 Composition API composable for interacting with the headless
 * Persona backend. Consumes the JSON shape returned by the Persona API
 * package (camelCase resources).
 */
export function usePersona(options: UsePersonaOptions = {}): UsePersonaReturn {
  const http: AxiosInstance =
    options.http ?? axios.create({ baseURL: options.baseURL ?? '/api/persona' });

  const loading = ref(false);
  const error = ref<string | null>(null);

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

  async function fetchProfile(personableType: string, personableId: number): Promise<Profile> {
    return run(async () => {
      const { data } = await http.get<Profile>('/profile', {
        params: { personable_type: personableType, personable_id: personableId },
      });
      return data;
    });
  }

  async function fetchContacts(
    personableType: string,
    personableId: number,
  ): Promise<Contact[]> {
    return run(async () => {
      const { data } = await http.get<Contact[]>('/contacts', {
        params: { personable_type: personableType, personable_id: personableId },
      });
      return data;
    });
  }

  async function addContact(
    personableType: string,
    personableId: number,
    input: CreateContactInput,
  ): Promise<Contact> {
    return run(async () => {
      const { data } = await http.post<Contact>('/contacts', {
        personable_type: personableType,
        personable_id: personableId,
        ...input,
      });
      return data;
    });
  }

  async function removeContact(contactId: number): Promise<void> {
    return run(async () => {
      await http.delete(`/contacts/${contactId}`);
    });
  }

  async function setPrimaryContact(contactId: number): Promise<void> {
    return run(async () => {
      await http.patch(`/contacts/${contactId}/primary`);
    });
  }

  return {
    loading,
    error: readonly(error),
    fetchProfile,
    fetchContacts,
    addContact,
    removeContact,
    setPrimaryContact,
  };
}