import { defineStore } from 'pinia';
import { router } from '@/router';

// Kredensial lokal — ganti sesuai kebutuhan
const MOCK_USERS = [
  { email: 'admin@royptsms.com', password: 'admin123', name: 'Roy Admin', role: 'Admin' },
  { email: 'user@royptsms.com', password: 'user123', name: 'JWT User', role: 'User' },
];

export const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    // @ts-ignore
    user: JSON.parse(localStorage.getItem('user')),
    returnUrl: null as string | null
  }),
  actions: {
    async login(username: string, password: string) {
      const found = MOCK_USERS.find(
        (u) => u.email.toLowerCase() === username.toLowerCase() && u.password === password
      );
      if (!found) {
        throw 'Email atau password salah';
      }
      const user = { id: 1, name: found.name, email: found.email, role: found.role };
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user));
      router.push(this.returnUrl || '/dashboard');
    },
    logout() {
      this.user = null;
      localStorage.removeItem('user');
      router.push('/login');
    }
  }
});
