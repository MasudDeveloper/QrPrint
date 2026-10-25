import React from 'react';
import { interpolate, spring, useCurrentFrame, useVideoConfig } from 'remotion';

// 1. ANIMATED PHYSICAL PRINTER
export const AnimatedPrinter: React.FC<{ size?: number; isPrinting?: boolean }> = ({
  size = 280,
  isPrinting = true,
}) => {
  const frame = useCurrentFrame();

  // Paper sliding out of printer
  const paperY = isPrinting ? interpolate(frame % 90, [0, 45, 90], [10, 80, 10]) : 20;
  const ledGlow = isPrinting ? Math.sin(frame * 0.15) * 0.5 + 0.5 : 0.8;

  return (
    <div style={{ width: size, height: size * 0.85, position: 'relative' }}>
      <svg
        viewBox="0 0 300 240"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        style={{ width: '100%', height: '100%', filter: 'drop-shadow(0 20px 30px rgba(0,0,0,0.6))' }}
      >
        <defs>
          <linearGradient id="bodyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="#1e293b" />
            <stop offset="100%" stopColor="#0f172a" />
          </linearGradient>
          <linearGradient id="trayGrad" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stopColor="#334155" />
            <stop offset="100%" stopColor="#1e293b" />
          </linearGradient>
          <linearGradient id="paperGrad" x1="0%" y1="0%" x2="0%" y2="100%">
            <stop offset="0%" stopColor="#ffffff" />
            <stop offset="100%" stopColor="#f1f5f9" />
          </linearGradient>
        </defs>

        {/* Top Paper Feed (Input Tray) */}
        <rect x="75" y="15" width="150" height="65" rx="8" fill="url(#trayGrad)" stroke="#475569" strokeWidth="3" />
        {/* Blank Input Paper */}
        <rect x="90" y="25" width="120" height="45" rx="4" fill="#ffffff" opacity="0.9" />

        {/* Emerging Printed Page */}
        <g transform={`translate(0, ${paperY})`}>
          <rect x="85" y="115" width="130" height="85" rx="6" fill="url(#paperGrad)" stroke="#cbd5e1" strokeWidth="2" />
          {/* Printed Content Lines */}
          <line x1="100" y1="135" x2="190" y2="135" stroke="#f43f5e" strokeWidth="3" strokeLinecap="round" />
          <line x1="100" y1="148" x2="200" y2="148" stroke="#334155" strokeWidth="2.5" strokeLinecap="round" />
          <line x1="100" y1="160" x2="175" y2="160" stroke="#334155" strokeWidth="2.5" strokeLinecap="round" />
          <line x1="100" y1="172" x2="195" y2="172" stroke="#10b981" strokeWidth="3" strokeLinecap="round" />
          {/* Small check icon */}
          <circle cx="195" cy="182" r="8" fill="#10b981" />
          <path d="M192 182L194 184L198 180" stroke="#ffffff" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
        </g>

        {/* Printer Main Body */}
        <rect x="30" y="70" width="240" height="110" rx="18" fill="url(#bodyGrad)" stroke="#334155" strokeWidth="4" />
        {/* Paper Eject Slot */}
        <rect x="70" y="120" width="160" height="12" rx="6" fill="#020617" />

        {/* Front Control Panel */}
        <rect x="50" y="85" width="90" height="24" rx="6" fill="#090d16" stroke="#1e293b" />
        {/* LED Status Light */}
        <circle cx="62" cy="97" r="4" fill="#10b981" style={{ opacity: ledGlow, filter: 'drop-shadow(0 0 6px #10b981)' }} />
        <rect x="74" y="93" width="55" height="8" rx="3" fill="#1e293b" />

        {/* Power Button */}
        <circle cx="240" cy="97" r="8" fill="#334155" stroke="#475569" strokeWidth="2" />
        <circle cx="240" cy="97" r="3" fill="#f43f5e" />
      </svg>
    </div>
  );
};

// 2. ANIMATED TECH QR CODE WITH LASER SCAN
export const AnimatedQrScanner: React.FC<{ size?: number }> = ({ size = 260 }) => {
  const frame = useCurrentFrame();

  // Laser beam moves up and down
  const beamY = interpolate(Math.sin(frame * 0.08), [-1, 1], [30, 230]);

  return (
    <div style={{ width: size, height: size, position: 'relative' }}>
      <svg
        viewBox="0 0 260 260"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        style={{ width: '100%', height: '100%' }}
      >
        <defs>
          <linearGradient id="qrLaser" x1="0%" y1="0%" x2="100%" y2="0%">
            <stop offset="0%" stopColor="transparent" />
            <stop offset="20%" stopColor="#10b981" />
            <stop offset="50%" stopColor="#34d399" />
            <stop offset="80%" stopColor="#10b981" />
            <stop offset="100%" stopColor="transparent" />
          </linearGradient>
        </defs>

        {/* QR Outer Frame Container */}
        <rect x="10" y="10" width="240" height="240" rx="24" fill="#0f172a" stroke="#1e293b" strokeWidth="4" />

        {/* 4 Corner Targeting Brackets */}
        <path d="M25 55V35C25 29.5 29.5 25 35 25H55" stroke="#f43f5e" strokeWidth="6" strokeLinecap="round" />
        <path d="M205 25H225C230.5 25 235 29.5 235 35V55" stroke="#f43f5e" strokeWidth="6" strokeLinecap="round" />
        <path d="M25 205V225C25 230.5 29.5 235 35 235H55" stroke="#f43f5e" strokeWidth="6" strokeLinecap="round" />
        <path d="M205 235H225C230.5 235 235 230.5 235 225V205" stroke="#f43f5e" strokeWidth="6" strokeLinecap="round" />

        {/* 3 Main QR Position Markers */}
        {/* Top Left */}
        <rect x="40" y="40" width="55" height="55" rx="10" fill="#ffffff" />
        <rect x="48" y="48" width="39" height="39" rx="6" fill="#0f172a" />
        <rect x="56" y="56" width="23" height="23" rx="4" fill="#ffffff" />

        {/* Top Right */}
        <rect x="165" y="40" width="55" height="55" rx="10" fill="#ffffff" />
        <rect x="173" y="48" width="39" height="39" rx="6" fill="#0f172a" />
        <rect x="181" y="56" width="23" height="23" rx="4" fill="#ffffff" />

        {/* Bottom Left */}
        <rect x="40" y="165" width="55" height="55" rx="10" fill="#ffffff" />
        <rect x="48" y="173" width="39" height="39" rx="6" fill="#0f172a" />
        <rect x="56" y="181" width="23" height="23" rx="4" fill="#ffffff" />

        {/* Stylized QR Data Matrix Dots */}
        <rect x="110" y="45" width="14" height="14" rx="3" fill="#94a3b8" />
        <rect x="135" y="45" width="14" height="25" rx="3" fill="#f43f5e" />
        <rect x="110" y="70" width="20" height="14" rx="3" fill="#ffffff" />
        <rect x="110" y="95" width="40" height="14" rx="3" fill="#10b981" />
        <rect x="45" y="110" width="20" height="20" rx="3" fill="#ffffff" />
        <rect x="75" y="110" width="14" height="35" rx="3" fill="#94a3b8" />
        <rect x="100" y="125" width="30" height="30" rx="4" fill="#f43f5e" />
        <rect x="145" y="115" width="25" height="14" rx="3" fill="#ffffff" />
        <rect x="180" y="110" width="20" height="20" rx="3" fill="#38bdf8" />
        <rect x="120" y="170" width="25" height="25" rx="4" fill="#ffffff" />
        <rect x="155" y="165" width="14" height="35" rx="3" fill="#10b981" />
        <rect x="180" y="180" width="30" height="14" rx="3" fill="#94a3b8" />
        <rect x="135" y="205" width="40" height="14" rx="3" fill="#ffffff" />
        <rect x="190" y="205" width="20" height="20" rx="3" fill="#f43f5e" />

        {/* Center Logo Placeholder */}
        <rect x="105" y="105" width="50" height="50" rx="14" fill="#090d16" stroke="#f43f5e" strokeWidth="2.5" />
        <text x="130" y="136" textAnchor="middle" fill="#ffffff" fontSize="22" fontWeight="900" fontFamily="sans-serif">
          PS
        </text>

        {/* Glowing Laser Scan Beam */}
        <line x1="20" y1={beamY} x2="240" y2={beamY} stroke="url(#qrLaser)" strokeWidth="5" />
        <line x1="30" y1={beamY} x2="230" y2={beamY} stroke="#ffffff" strokeWidth="1.5" opacity="0.8" />
      </svg>
    </div>
  );
};

// 3. ANIMATED SMARTPHONE PREVIEW
export const AnimatedSmartphone: React.FC<{ size?: number }> = ({ size = 260 }) => {
  return (
    <div style={{ width: size, height: size * 1.85, position: 'relative' }}>
      <svg
        viewBox="0 0 240 440"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
        style={{ width: '100%', height: '100%', filter: 'drop-shadow(0 25px 40px rgba(0,0,0,0.7))' }}
      >
        <defs>
          <linearGradient id="phoneBorder" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="#475569" />
            <stop offset="100%" stopColor="#1e293b" />
          </linearGradient>
        </defs>

        {/* Outer Phone Case */}
        <rect x="8" y="8" width="224" height="424" rx="36" fill="#090d16" stroke="url(#phoneBorder)" strokeWidth="6" />

        {/* Dynamic Island / Camera Notch */}
        <rect x="85" y="18" width="70" height="16" rx="8" fill="#000000" />
        <circle cx="140" cy="26" r="3" fill="#1e293b" />

        {/* Screen Display Content */}
        {/* Header App Bar */}
        <rect x="22" y="45" width="196" height="40" rx="12" fill="#0f172a" />
        <circle cx="42" cy="65" r="10" fill="#f43f5e" />
        <text x="60" y="70" fill="#ffffff" fontSize="13" fontWeight="bold" fontFamily="sans-serif">PrintSheba</text>
        <rect x="180" y="58" width="24" height="14" rx="4" fill="#10b981" />

        {/* File Upload Box */}
        <rect x="22" y="98" width="196" height="110" rx="16" fill="#1e293b" stroke="#334155" strokeDasharray="6 4" />
        <circle cx="120" cy="135" r="18" fill="rgba(244,63,94,0.15)" />
        <path d="M120 125V145M110 135L120 125L130 135" stroke="#f43f5e" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
        <text x="120" y="170" textAnchor="middle" fill="#ffffff" fontSize="12" fontWeight="bold" fontFamily="sans-serif">Report_Final.pdf</text>
        <text x="120" y="188" textAnchor="middle" fill="#94a3b8" fontSize="10" fontFamily="sans-serif">১২ পেজ • ২.৪ MB</text>

        {/* Options Card */}
        <rect x="22" y="220" width="196" height="85" rx="14" fill="#0f172a" stroke="#1e293b" />
        <text x="36" y="245" fill="#94a3b8" fontSize="10" fontFamily="sans-serif">প্রিন্ট মোড</text>
        <rect x="36" y="255" width="75" height="26" rx="6" fill="#10b981" />
        <text x="73" y="272" textAnchor="middle" fill="#ffffff" fontSize="11" fontWeight="bold" fontFamily="sans-serif">সাদাকালো</text>
        <rect x="120" y="255" width="80" height="26" rx="6" fill="#1e293b" />
        <text x="160" y="272" textAnchor="middle" fill="#94a3b8" fontSize="11" fontFamily="sans-serif">কালার</text>
        <text x="36" y="295" fill="#fcd34d" fontSize="11" fontWeight="bold" fontFamily="sans-serif">মোট বিল: ৳৬০</text>

        {/* Big Print Button */}
        <rect x="22" y="325" width="196" height="48" rx="14" fill="#f43f5e" />
        <text x="120" y="355" textAnchor="middle" fill="#ffffff" fontSize="15" fontWeight="900" fontFamily="sans-serif">
          🖨️ ওয়ান-ক্লিক প্রিন্ট
        </text>

        {/* Bottom Home Indicator */}
        <rect x="85" y="415" width="70" height="4" rx="2" fill="#475569" />
      </svg>
    </div>
  );
};

// 4. ANIMATED PRIVACY SHIELD WITH LOCK
export const AnimatedPrivacyShield: React.FC<{ size?: number }> = ({ size = 200 }) => {
  const frame = useCurrentFrame();
  const pulse = Math.sin(frame * 0.1) * 0.05 + 1;

  return (
    <div style={{ width: size, height: size, transform: `scale(${pulse})` }}>
      <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ width: '100%', height: '100%' }}>
        <defs>
          <linearGradient id="shieldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stopColor="#10b981" />
            <stop offset="100%" stopColor="#047857" />
          </linearGradient>
        </defs>

        {/* Outer Glow Shield */}
        <path
          d="M100 20L165 50V105C165 145 137 175 100 188C63 175 35 145 35 105V50L100 20Z"
          fill="url(#shieldGrad)"
          stroke="#6ee7b7"
          strokeWidth="6"
        />

        {/* Padlock Icon */}
        <rect x="75" y="95" width="50" height="40" rx="8" fill="#ffffff" />
        <path d="M85 95V80C85 71.7 91.7 65 100 65C108.3 65 115 71.7 115 80V95" stroke="#ffffff" strokeWidth="7" strokeLinecap="round" />
        <circle cx="100" cy="112" r="4" fill="#047857" />
        <path d="M100 116V124" stroke="#047857" strokeWidth="3" strokeLinecap="round" />
      </svg>
    </div>
  );
};

// 5. ANIMATED TANGLED CABLE & DANGER (For Hook/Problems)
export const AnimatedTangledCables: React.FC<{ size?: number }> = ({ size = 240 }) => {
  const frame = useCurrentFrame();
  const spark = Math.sin(frame * 0.3) > 0.4;

  return (
    <div style={{ width: size, height: size * 0.8 }}>
      <svg viewBox="0 0 240 180" fill="none" xmlns="http://www.w3.org/2000/svg" style={{ width: '100%', height: '100%' }}>
        {/* Chaotic Wires */}
        <path d="M20 140C60 40 120 170 160 50C180 -10 90 20 220 120" stroke="#ef4444" strokeWidth="6" strokeLinecap="round" />
        <path d="M30 30C120 150 70 80 180 150C210 180 200 40 230 70" stroke="#f59e0b" strokeWidth="5" strokeLinecap="round" opacity="0.8" />
        
        {/* Disconnected USB Plugs */}
        <rect x="15" y="125" width="30" height="20" rx="4" fill="#334155" stroke="#ef4444" strokeWidth="3" />
        <rect x="195" y="110" width="30" height="20" rx="4" fill="#334155" stroke="#ef4444" strokeWidth="3" />

        {/* Sparks */}
        {spark && (
          <g transform="translate(140, 50)">
            <path d="M0 -15L5 -5L15 0L5 5L0 15L-5 5L-15 0L-5 -5Z" fill="#fcd34d" />
          </g>
        )}

        {/* Big Red Cross / Fail Badge */}
        <circle cx="120" cy="90" r="32" fill="#ef4444" stroke="#ffffff" strokeWidth="4" />
        <path d="M108 78L132 102M132 78L108 102" stroke="#ffffff" strokeWidth="6" strokeLinecap="round" />
      </svg>
    </div>
  );
};
