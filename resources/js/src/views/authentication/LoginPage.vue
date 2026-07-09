<script setup lang="ts">
import Logo from '@/layouts/dashboard/logo/LogoDark.vue';
import AuthLogin from './authForms/AuthLogin.vue';
import AuthFooter from './auth/AuthFooter.vue';
</script>

<template>
  <div class="animated-gradient-bg" style="min-height: 100vh; width: 100vw; display: flex; flex-direction: column; position: relative;">
    <!-- Top Logo -->
    <div style="padding: 24px; position: absolute; top: 0; left: 0; z-index: 10;">
      <Logo class="bg-white rounded px-2 py-1 shadow-sm d-inline-block" style="opacity: 0.9;" />
    </div>

    <!-- Login Centered Content -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; padding: 24px; z-index: 1;">
      <v-card class="glass-login-card elevation-10 rounded-xl overflow-hidden" style="width: 100%; max-width: 480px;">
        <!-- Top decorative bar -->
        <div class="glass-decoration-bar"></div>
        
        <v-card-text class="pa-sm-10 pa-6 position-relative">
          <AuthLogin />
        </v-card-text>
      </v-card>
    </div>

    <!-- Footer -->
    <div class="auth-footer-wrapper" style="width: 100%; padding-bottom: 16px; z-index: 1;">
      <AuthFooter />
    </div>
  </div>
</template>

<style lang="scss">
.animated-gradient-bg {
  /* Rich Corporate Blue & Teal Gradient */
  background: linear-gradient(-45deg, #002244, #1976d2, #00acc1, #005f73);
  background-size: 400% 400%;
  animation: gradientBG 15s ease infinite;
  position: relative;
  overflow: hidden;
}

/* Subtle floating light effect behind */
.animated-gradient-bg::before {
  content: '';
  position: absolute;
  top: -20%;
  left: -10%;
  width: 50vw;
  height: 50vw;
  background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
  border-radius: 50%;
  animation: floatLight 10s ease-in-out infinite alternate;
}

.animated-gradient-bg::after {
  content: '';
  position: absolute;
  bottom: -20%;
  right: -10%;
  width: 60vw;
  height: 60vw;
  background: radial-gradient(circle, rgba(0, 172, 193, 0.2) 0%, rgba(255,255,255,0) 70%);
  border-radius: 50%;
  animation: floatLight2 12s ease-in-out infinite alternate;
}

@keyframes floatLight {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(50px, 50px) scale(1.1); }
}
@keyframes floatLight2 {
  0% { transform: translate(0, 0) scale(1); }
  100% { transform: translate(-50px, -50px) scale(1.15); }
}

@keyframes gradientBG {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.z-index-1 {
  z-index: 1;
}

.glass-login-card {
  /* Premium Glassmorphism effect */
  background: rgba(255, 255, 255, 0.9) !important;
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.6) !important;
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25) !important;
  transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
  
  &:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 50px rgba(0, 0, 0, 0.3) !important;
  }
}

.glass-decoration-bar {
  height: 6px;
  width: 100%;
  background: linear-gradient(90deg, #1976d2, #00acc1);
}

/* Ensure the footer text is readable against the dark background */
.auth-footer-wrapper .text-medium-emphasis, 
.auth-footer-wrapper .text-darkText,
.auth-footer-wrapper a {
  color: rgba(255, 255, 255, 0.85) !important;
}

.auth-footer-wrapper a:hover {
  color: #fff !important;
  text-decoration: underline;
}
</style>
