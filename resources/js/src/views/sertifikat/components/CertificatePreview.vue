<template>
  <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" fullscreen transition="dialog-bottom-transition">
    <v-card class="cert-preview-bg">
      <v-toolbar color="primary" class="cert-no-print">
        <v-btn icon="mdi-close" @click="$emit('update:modelValue', false)"></v-btn>
        <v-toolbar-title>Preview Sertifikat</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn variant="text" @click="printCertificate" prepend-icon="mdi-printer">Print / Simpan PDF</v-btn>
        </v-toolbar-items>
      </v-toolbar>

      <div class="cert-preview-scroll" v-if="sertifikat">
        <!-- A4 Landscape paper (29.7cm x 21cm) -->
        <div class="cert-a4" id="printable-certificate">
          
          <!-- Outer border frame (27.6cm x 19.4cm) -->
          <div class="cert-border-frame">
            <img src="/images/border.png" class="cert-border-img" alt="border" />
            
            <!-- Text box (24cm x 16.2cm) positioned on top of the border image -->
            <div class="cert-body">

              <!-- Row: PT name (left) — NO (right) -->
              <div class="cert-row-header">
                <span>{{ sertifikat.nama_pt }}</span>
                <span>NO : {{ sertifikat.nomor_sertifikat }}</span>
              </div>

              <!-- Title -->
              <div class="cert-main-title">Certificate of Calibration</div>

              <!-- Equipment -->
              <div class="cert-equipment">{{ sertifikat.nama_alat }}</div>

              <!-- Granted to -->
              <div class="cert-granted-text">Is hereby granted to :</div>

              <!-- Hospital -->
              <div class="cert-hospital">{{ sertifikat.nama_rs }}</div>

              <!-- Details -->
              <div class="cert-info">
                <p>Serial Number : {{ sertifikat.nomor_sn }}</p>
                <p>Date of Calibration : {{ formatDate(sertifikat.tanggal_kalibrasi) }}</p>
                <p>Valid Until : {{ formatDate(sertifikat.valid_until) }}</p>
              </div>

              <!-- Result -->
              <div class="cert-result-label">The Final Result :</div>
              <div class="cert-passed-text">P A S S E D</div>

              <!-- Spacer to push signatures down -->
              <div class="cert-spacer"></div>

              <!-- Signatures row -->
              <div class="cert-sig-row">
                <div class="cert-sig-col">
                  <div class="cert-sig-pad"></div>
                  <div class="cert-sig-name">{{ sertifikat.penandatangan1_nama }}</div>
                  <div class="cert-sig-line"></div>
                  <div class="cert-sig-title">{{ sertifikat.penandatangan1_jabatan }}</div>
                </div>
                <div class="cert-sig-col">
                  <div class="cert-sig-pad"></div>
                  <div class="cert-sig-name">{{ sertifikat.penandatangan2_nama }}</div>
                  <div class="cert-sig-line"></div>
                  <div class="cert-sig-title">{{ sertifikat.penandatangan2_jabatan }}</div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
const props = defineProps<{
  modelValue: boolean;
  sertifikat: any;
}>();

const emit = defineEmits(['update:modelValue']);

function getOrdinalSuffix(i: number) {
  const j = i % 10, k = i % 100;
  if (j === 1 && k !== 11) return i + "st";
  if (j === 2 && k !== 12) return i + "nd";
  if (j === 3 && k !== 13) return i + "rd";
  return i + "th";
}

function formatDate(dateStr: string) {
  if (!dateStr) return '';
  const date = new Date(dateStr);
  const months = ["January","February","March","April","May","June",
    "July","August","September","October","November","December"];
  return `${months[date.getMonth()]} ${getOrdinalSuffix(date.getDate())}, ${date.getFullYear()}`;
}

function printCertificate() {
  window.print();
}
</script>

<style>
/* ===== Font Declarations pointing to verified local copy files inside public/fonts/ ===== */
@font-face {
  font-family: 'Algerian';
  src: url('/fonts/ALGER.TTF') format('truetype');
}
@font-face {
  font-family: 'Perpetua';
  src: url('/fonts/PER.TTF') format('truetype');
  font-weight: normal;
  font-style: normal;
}
@font-face {
  font-family: 'Perpetua';
  src: url('/fonts/PERB.TTF') format('truetype');
  font-weight: bold;
  font-style: normal;
}
@font-face {
  font-family: 'Lucida Calligraphy';
  src: url('/fonts/LCALLIG.TTF') format('truetype');
}
@font-face {
  font-family: 'Brush Script MT';
  src: url('/fonts/BRUSHSCI.TTF') format('truetype');
}
@font-face {
  font-family: 'Perpetua Titling MT';
  src: url('/fonts/PERTILI.TTF') format('truetype');
  font-weight: normal;
}
@font-face {
  font-family: 'Perpetua Titling MT';
  src: url('/fonts/PERTIBD.TTF') format('truetype');
  font-weight: bold;
}

/* ===== Preview wrapper ===== */
.cert-preview-bg { background: #d0d0d0 !important; }

.cert-preview-scroll {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 20px;
  overflow: auto;
  min-height: calc(100vh - 64px);
}

/* ===== A4 Landscape: 297mm × 210mm ===== */
.cert-a4 {
  width: 297mm;
  height: 210mm;
  background: #fff;
  box-shadow: 0 4px 24px rgba(0,0,0,.2);
  flex-shrink: 0;
  position: relative;
  overflow: hidden;
}

/* ===== Border frame — 27.6cm × 19.4cm centered in A4 ===== */
.cert-border-frame {
  position: absolute;
  /* Center: (297mm-276mm)/2 = 10.5mm, (210mm-194mm)/2 = 8mm */
  top: 8mm;
  left: 10.5mm;
  width: 276mm;
  height: 194mm;
  box-sizing: border-box;
}

.cert-border-img {
  width: 100% !important;
  height: 100% !important;
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1;
}

/* ===== Text box — 24cm × 16.2cm centered inside the border ===== */
.cert-body {
  position: absolute;
  z-index: 2; /* Render on top of the border img */
  /* Center: (276mm-240mm)/2 = 18mm, (194mm-162mm)/2 = 16mm */
  top: 16mm;
  left: 18mm;
  width: 240mm;
  height: 162mm;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 0 !important; /* Zero padding to align children to absolute corners */
  box-sizing: border-box;
  background-color: white !important; /* White background inside the frame */
  border: 1.5px solid #1c6293 !important; /* Thin border bounding the white box */
  
  /* Default font: Perpetua 20pt normal */
  font-family: 'Perpetua', serif !important;
  font-size: 20pt !important;
  font-weight: normal !important;
  color: #000 !important;
}

/* --- Header row: PT name & NO — Perpetua 20pt bold --- */
.cert-row-header {
  width: 100%;
  display: flex;
  justify-content: space-between;
  font-family: 'Perpetua', serif !important;
  font-size: 20pt !important;
  font-weight: bold !important;
  padding: 12px 16px 0 !important; /* Pushes text exactly to the top corners */
  margin-bottom: 2px;
}

/* --- Title: Algerian 26pt bold --- */
.cert-main-title {
  font-family: 'Algerian', fantasy !important;
  font-size: 26pt !important;
  font-weight: bold !important;
  letter-spacing: 6px !important; /* Very loose letter spacing */
  text-transform: uppercase;
  border-bottom: 2.5px solid #000 !important;
  padding-bottom: 2px;
  margin: 22px 0 10px !important; /* Slightly reduced top margin to fit inside A4 vertically */
  text-align: center;
}

/* --- Equipment name: Lucida Calligraphy 24pt --- */
.cert-equipment {
  font-family: 'Lucida Calligraphy', cursive !important;
  font-size: 24pt !important;
  font-weight: normal !important;
  margin: 6px 0 4px !important;
  text-align: center;
}

/* --- Granted to: Brush Script MT 24pt --- */
.cert-granted-text {
  font-family: 'Brush Script MT', cursive !important;
  font-size: 24pt !important;
  font-weight: normal !important;
  margin: 8px 0 4px !important;
  text-align: center;
}

/* --- Hospital name: Perpetua Titling MT 22pt bold blue --- */
.cert-hospital {
  font-family: 'Perpetua Titling MT', serif !important;
  font-size: 22pt !important;
  font-weight: bold !important;
  letter-spacing: 6px !important; /* Very loose letter spacing */
  color: #1a5c8a !important;
  border-bottom: 2.5px solid #000 !important;
  padding-bottom: 2px;
  margin: 4px 0 8px !important;
  text-align: center;
}

/* --- Info: Perpetua 20pt --- */
.cert-info {
  text-align: center;
  margin: 6px 0 4px !important;
}
.cert-info p {
  font-family: 'Perpetua', serif !important;
  font-size: 20pt !important;
  font-weight: normal !important;
  margin: 1px 0;
}

/* --- Final Result label: Lucida Calligraphy 18pt --- */
.cert-result-label {
  font-family: 'Lucida Calligraphy', cursive !important;
  font-size: 18pt !important;
  font-weight: normal !important;
  margin: 8px 0 0 !important;
  text-align: center;
}

/* --- PASSED: Perpetua 22pt bold --- */
.cert-passed-text {
  font-family: 'Perpetua', serif !important;
  font-size: 22pt !important;
  font-weight: bold !important;
  letter-spacing: 4px;
  margin: 4px 0 0 !important;
  text-align: center;
}

/* --- Spacer pushes signatures down --- */
.cert-spacer { flex: 1; }

/* --- Signatures: Perpetua 20pt --- */
.cert-sig-row {
  width: 100%;
  display: flex;
  justify-content: space-between;
  padding: 0 24px 10px !important; /* Positions exactly at the bottom corners */
}
.cert-sig-col {
  width: 300px;
  text-align: center;
}
.cert-sig-pad { height: 25px; } /* Reduced from 45px to pull signatures up */
.cert-sig-line {
  border-top: 1.5px solid #000;
  margin-top: 6px !important;
  margin-bottom: 6px !important;
}
.cert-sig-name {
  font-family: 'Perpetua', serif !important;
  font-size: 20pt !important;
  font-weight: normal !important;
}
.cert-sig-title {
  font-family: 'Perpetua', serif !important;
  font-size: 20pt !important;
  font-weight: normal !important;
}

/* ===== PRINT ===== */
@media print {
  @page {
    size: A4 landscape;
    margin: 0;
  }
  body {
    margin: 0 !important;
    padding: 0 !important;
    background: #fff !important;
  }
  body * {
    visibility: hidden !important;
  }
  .cert-no-print {
    display: none !important;
  }
  #printable-certificate,
  #printable-certificate * {
    visibility: visible !important;
  }
  #printable-certificate {
    position: fixed !important;
    left: 0 !important;
    top: 0 !important;
    width: 297mm !important;
    height: 210mm !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
  }
  .cert-preview-scroll {
    padding: 0 !important;
  }
}
</style>
