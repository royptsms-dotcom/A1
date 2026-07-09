<script setup lang="ts">
import { ref } from 'vue';
// icons
import { EyeInvisibleOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { useAuthStore } from '@/stores/auth';
import { Form } from 'vee-validate';

const checkbox = ref(false);
const valid = ref(false);
const show1 = ref(false);
const password = ref('admin123');
const username = ref('admin@royptsms.com');
// Password validation rules
const passwordRules = ref([
  (v: string) => !!v || 'Password is required',
  (v: string) => v === v.trim() || 'Password cannot start or end with spaces',
  (v: string) => v.length <= 10 || 'Password must be less than 10 characters'
]);
// Email validation rules
const emailRules = ref([
  (v: string) => !!v.trim() || 'E-mail is required',
  (v: string) => {
    const trimmedEmail = v.trim();
    return !/\s/.test(trimmedEmail) || 'E-mail must not contain spaces';
  },
  (v: string) => /.+@.+\..+/.test(v.trim()) || 'E-mail must be valid'
]);

/* eslint-disable @typescript-eslint/no-explicit-any */
function validate(values: any, { setErrors }: any) {
  // Trim the username before validation
  const trimmedUsername = username.value.trim();

  // Update the username with trimmed value
  username.value = trimmedUsername;

  const authStore = useAuthStore();
  return authStore.login(trimmedUsername, password.value).catch((error) => setErrors({ apiError: error }));
}
</script>

<template>
  <div class="custom-font-wrapper">
    <div class="d-flex justify-center mb-8 mt-4">
      <h2 class="font-weight-bold text-darkText mb-0" style="letter-spacing: -0.5px; font-size: 2.2rem;">Welcome Back</h2>
    </div>
    <Form @submit="validate" class="loginForm" v-slot="{ errors, isSubmitting }">
    <div class="mb-5">
      <v-label class="text-subtitle-2 font-weight-medium mb-2 text-darkText">Email Address</v-label>
      <v-text-field
        aria-label="email address"
        v-model="username"
        :rules="emailRules"
        required
        hide-details="auto"
        variant="outlined"
        color="primary"
        rounded="lg"
        class="glass-input"
        placeholder="Enter your email"
        @input="username"
      ></v-text-field>
    </div>
    <div>
      <v-label class="text-subtitle-2 font-weight-medium mb-2 text-darkText">Password</v-label>
      <v-text-field
        aria-label="password"
        v-model="password"
        :rules="passwordRules"
        required
        variant="outlined"
        color="primary"
        rounded="lg"
        hide-details="auto"
        :type="show1 ? 'text' : 'password'"
        class="glass-input"
        placeholder="Enter your password"
        @input="password"
      >
        <template v-slot:append-inner>
          <v-btn color="secondary" icon rounded variant="text">
            <EyeInvisibleOutlined :style="{ color: 'rgb(var(--v-theme-secondary))' }" v-if="show1 == false" @click="show1 = !show1" />
            <EyeOutlined :style="{ color: 'rgb(var(--v-theme-secondary))' }" v-if="show1 == true" @click="show1 = !show1" />
          </v-btn>
        </template>
      </v-text-field>
    </div>

    <div class="d-flex align-center mt-3 mb-6">
      <v-checkbox
        v-model="checkbox"
        :rules="[(v: any) => !!v || 'You must agree to continue!']"
        label="Keep me signed in"
        required
        color="primary"
        class="ms-n2"
        hide-details
      ></v-checkbox>
    </div>
    <v-btn 
      color="primary" 
      :loading="isSubmitting" 
      block 
      variant="flat" 
      size="large" 
      rounded="lg"
      :disabled="valid" 
      type="submit"
      class="animated-btn font-weight-bold"
    >
      Sign In
    </v-btn>
    <div v-if="errors.apiError" class="mt-4">
      <v-alert color="error" variant="tonal" rounded="lg">{{ errors.apiError }}</v-alert>
    </div>
  </Form>
  </div>
</template>

<style lang="scss">
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap');

.custom-font-wrapper {
  font-family: 'Outfit', sans-serif !important;

  h2, h3, h4, h5, h6, .v-label, .v-btn, input, .v-alert {
    font-family: 'Outfit', sans-serif !important;
  }
}

.loginForm {
  .v-text-field .v-field--variant-outlined {
    background-color: rgba(255, 255, 255, 0.5) !important;
    transition: background-color 0.3s ease, border-color 0.3s ease;
  }
  
  .v-text-field .v-field--focused.v-field--variant-outlined {
    background-color: #fff !important;
  }
}

.hover-underline:hover {
  text-decoration: underline !important;
}

.animated-btn {
  transition: all 0.3s ease !important;
  box-shadow: 0 4px 15px rgba(25, 118, 210, 0.4) !important;
  
  &:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(25, 118, 210, 0.6) !important;
  }
  &:active {
    transform: translateY(1px);
  }
}

.loginForm .v-text-field .v-field--active input {
  font-weight: 500;
}
</style>
