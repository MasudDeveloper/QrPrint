import React from 'react';

export interface AgentWindowModalProps {
  status?: string;
  shopName?: string;
  serverUrl?: string;
  apiToken?: string;
  bwPrinter?: string;
  colorPrinter?: string;
  isSaved?: boolean;
  isTestPrinting?: boolean;
  isMinimizedToTray?: boolean;
}

export const MockAgentWindowModal: React.FC<AgentWindowModalProps> = ({
  status = '● ক্লাউড সিঙ্ক সক্রিয় (Ready)',
  shopName = 'গ্রিন সাইবার ক্যাফে ও ফটোকপি',
  serverUrl = 'https://printsheba.com',
  apiToken = 'ps_live_89f02c4819e4a3b70e',
  bwPrinter = 'HP LaserJet Pro MFP M428fdw',
  colorPrinter = 'Epson EcoTank L8050 Photo',
  isSaved = true,
  isTestPrinting = false,
  isMinimizedToTray = false,
}) => {
  return (
    <div
      style={{
        width: 1280,
        height: 720,
        backgroundColor: '#0c1222',
        backgroundImage: 'radial-gradient(circle at 50% 50%, #1e293b 0%, #020617 100%)',
        borderRadius: 24,
        border: '2px solid #334155',
        boxShadow: '0 30px 70px -15px rgba(0,0,0,0.9)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        position: 'relative',
      }}
    >
      {/* Windows Desktop Top Wallpaper Area */}
      <div style={{ flex: 1, display: 'flex', alignItems: 'center', justifyContent: 'center', position: 'relative', padding: 20 }}>
        
        {/* Desktop Icons on left */}
        <div style={{ position: 'absolute', top: 30, left: 30, display: 'flex', flexDirection: 'column', gap: 24, alignItems: 'center' }}>
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 4 }}>
            <span style={{ fontSize: 32 }}>💻</span>
            <span style={{ fontSize: 10, color: '#fff', textShadow: '0 1px 2px #000' }}>This PC</span>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 4 }}>
            <span style={{ fontSize: 32 }}>🌐</span>
            <span style={{ fontSize: 10, color: '#fff', textShadow: '0 1px 2px #000' }}>Chrome</span>
          </div>
          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 4, padding: '4px 6px', borderRadius: 8, backgroundColor: 'rgba(255,255,255,0.15)', border: '1px solid rgba(255,255,255,0.3)' }}>
            <span style={{ fontSize: 32 }}>📦</span>
            <span style={{ fontSize: 10, color: '#fff', fontWeight: 800, textShadow: '0 1px 2px #000' }}>PrintSheba.zip</span>
          </div>
        </div>

        {/* Windows Protected Your PC / SmartScreen Popover (Shown briefly if wanted, or the agent form itself) */}
        {!isMinimizedToTray && (
          <div
            style={{
              width: 820,
              backgroundColor: '#0f172a',
              borderRadius: 16,
              border: '1.5px solid #334155',
              boxShadow: '0 25px 60px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.1)',
              overflow: 'hidden',
              display: 'flex',
              flexDirection: 'column',
              color: '#ffffff',
            }}
          >
            {/* Windows Form Titlebar */}
            <div
              style={{
                height: 38,
                backgroundColor: '#090d16',
                borderBottom: '1px solid #1e293b',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '0 14px',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <span style={{ fontSize: 14 }}>🖨️</span>
                <span style={{ fontSize: 12, fontWeight: 900, letterSpacing: '0.02em', color: '#e2e8f0' }}>
                  PrintSheba (প্রিন্ট সেবা) ক্লাউড প্রিন্ট এজেন্ট v2.6
                </span>
              </div>
              <div style={{ display: 'flex', gap: 8, alignItems: 'center' }}>
                <span style={{ width: 12, height: 2, backgroundColor: '#94a3b8' }} />
                <span style={{ width: 10, height: 10, border: '1.5px solid #94a3b8' }} />
                <span style={{ color: '#ef4444', fontSize: 14, fontWeight: 900 }}>✕</span>
              </div>
            </div>

            {/* Header with status badges */}
            <div style={{ padding: '14px 20px', backgroundColor: '#0b1120', borderBottom: '1px solid #1e293b', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
                <span
                  style={{
                    padding: '4px 12px',
                    borderRadius: 9999,
                    backgroundColor: 'rgba(16,185,129,0.2)',
                    border: '1px solid #10b981',
                    color: '#34d399',
                    fontSize: 11,
                    fontWeight: 900,
                  }}
                >
                  {status}
                </span>
                <span style={{ fontSize: 12, color: '#cbd5e1', fontWeight: 700 }}>
                  {shopName}
                </span>
              </div>
              <div style={{ fontSize: 11, color: '#94a3b8' }}>
                জিরো-রিটেনশন ক্যাশ: <code style={{ color: '#38bdf8' }}>%TEMP%\PrintSheba</code>
              </div>
            </div>

            {/* Form Settings Grid */}
            <div style={{ padding: '18px 20px', display: 'grid', gridTemplateColumns: '1.2fr 1fr', gap: 18 }}>
              {/* Left Column: Printers & Server */}
              <div style={{ display: 'flex', flexDirection: 'column', gap: 12 }}>
                <div>
                  <label style={{ fontSize: 11, fontWeight: 800, color: '#94a3b8', display: 'block', marginBottom: 4 }}>
                    সাদাকালো (B&W) প্রিন্টার
                  </label>
                  <div style={{ padding: '8px 12px', borderRadius: 8, backgroundColor: '#020617', border: '1.5px solid #10b981', fontSize: 12, color: '#ffffff', fontWeight: 800, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span>{bwPrinter}</span>
                    <span style={{ color: '#10b981', fontSize: 10 }}>✓ সিলেক্টেড</span>
                  </div>
                </div>

                <div>
                  <label style={{ fontSize: 11, fontWeight: 800, color: '#94a3b8', display: 'block', marginBottom: 4 }}>
                    রঙিন (Color) প্রিন্টার
                  </label>
                  <div style={{ padding: '8px 12px', borderRadius: 8, backgroundColor: '#020617', border: '1.5px solid #ec4899', fontSize: 12, color: '#ffffff', fontWeight: 800, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                    <span>{colorPrinter}</span>
                    <span style={{ color: '#ec4899', fontSize: 10 }}>✓ কালার অটো-রাউট</span>
                  </div>
                </div>

                {/* Checkboxes */}
                <div style={{ display: 'flex', gap: 16, marginTop: 4 }}>
                  <label style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, color: '#cbd5e1' }}>
                    <span style={{ color: '#10b981', fontWeight: 900 }}>☑</span> উইন্ডোজ চালুর সাথে অটো-স্টার্ট
                  </label>
                  <label style={{ display: 'flex', alignItems: 'center', gap: 6, fontSize: 11, color: '#cbd5e1' }}>
                    <span style={{ color: '#10b981', fontWeight: 900 }}>☑</span> সিস্টেম ট্রেতে মিনিমাইজ
                  </label>
                </div>

                {/* Buttons */}
                <div style={{ display: 'flex', gap: 10, marginTop: 8 }}>
                  <div
                    style={{
                      flex: 1,
                      padding: '10px',
                      borderRadius: 10,
                      backgroundColor: '#10b981',
                      color: '#000000',
                      fontSize: 12,
                      fontWeight: 900,
                      textAlign: 'center',
                    }}
                  >
                    💾 সেটিংস সেভ করুন
                  </div>
                  <div
                    style={{
                      padding: '10px 16px',
                      borderRadius: 10,
                      backgroundColor: isTestPrinting ? '#3b82f6' : '#1e293b',
                      color: '#ffffff',
                      border: '1px solid #334155',
                      fontSize: 12,
                      fontWeight: 800,
                      textAlign: 'center',
                    }}
                  >
                    🖨️ {isTestPrinting ? 'টেস্ট পেজ প্রিন্ট হচ্ছে...' : 'টেস্ট প্রিন্ট পাঠান'}
                  </div>
                </div>
              </div>

              {/* Right Column: Live Spooler Terminal Log */}
              <div
                style={{
                  backgroundColor: '#020617',
                  borderRadius: 12,
                  border: '1px solid #1e293b',
                  padding: '12px',
                  fontFamily: "'Courier New', Courier, monospace",
                  fontSize: 10,
                  color: '#94a3b8',
                  display: 'flex',
                  flexDirection: 'column',
                  gap: 6,
                  height: 180,
                  overflowY: 'hidden',
                }}
              >
                <div style={{ color: '#10b981', fontWeight: 700 }}>[10:14:02] ক্লাউড এজেন্ট সার্ভারে সংযুক্ত হয়েছে।</div>
                <div>[10:14:03] প্রিন্টার স্ক্যান: 2টি ফিজিক্যাল প্রিন্টার পাওয়া গেছে।</div>
                <div>[10:14:05] কনফিগারেশন config.json লোড হয়েছে।</div>
                <div style={{ color: '#38bdf8' }}>[10:14:10] পোলিং শুরু... কোনো পেন্ডিং প্রিন্ট নেই।</div>
                <div style={{ color: '#34d399', backgroundColor: 'rgba(16,185,129,0.1)', padding: '2px 4px', borderRadius: 4 }}>
                  ✓ রেডি: কাস্টমার অর্ডার আসা মাত্র অটোমেটিক প্রিন্ট হবে।
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Minimized notification toast if minimized */}
        {isMinimizedToTray && (
          <div
            style={{
              position: 'absolute',
              bottom: 80,
              right: 40,
              width: 320,
              padding: '16px',
              borderRadius: 16,
              backgroundColor: '#0f172a',
              border: '1.5px solid #10b981',
              boxShadow: '0 15px 35px rgba(0,0,0,0.8)',
              display: 'flex',
              gap: 12,
              alignItems: 'center',
            }}
          >
            <span style={{ fontSize: 24 }}>🖨️</span>
            <div>
              <div style={{ fontSize: 13, fontWeight: 900, color: '#ffffff' }}>PrintSheba ব্যাকগ্রাউন্ডে চলছে</div>
              <div style={{ fontSize: 11, color: '#94a3b8' }}>সিস্টেম ট্রেতে সচল • প্রিন্ট জব আসলেই অটো প্রিন্ট হবে</div>
            </div>
          </div>
        )}
      </div>

      {/* Windows 11 Taskbar at Bottom */}
      <div
        style={{
          height: 48,
          backgroundColor: 'rgba(15, 23, 42, 0.95)',
          borderTop: '1px solid #334155',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '0 20px',
        }}
      >
        <div style={{ display: 'flex', gap: 12, alignItems: 'center' }}>
          <span style={{ fontSize: 18 }}>🪟</span>
          <span style={{ fontSize: 16 }}>📁</span>
          <span style={{ fontSize: 16 }}>🌐</span>
        </div>

        {/* Windows System Tray Area */}
        <div style={{ display: 'flex', gap: 12, alignItems: 'center' }}>
          {/* PrintSheba Tray Icon */}
          <div
            style={{
              padding: '3px 10px',
              borderRadius: 8,
              backgroundColor: 'rgba(16,185,129,0.2)',
              border: '1px solid #10b981',
              display: 'flex',
              alignItems: 'center',
              gap: 6,
            }}
          >
            <span style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: '#10b981', boxShadow: '0 0 6px #10b981' }} />
            <span style={{ fontSize: 11, fontWeight: 900, color: '#34d399' }}>PrintSheba Agent</span>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'flex-end', fontSize: 10, color: '#94a3b8' }}>
            <span>10:30 AM</span>
            <span>25/10/2026</span>
          </div>
        </div>
      </div>
    </div>
  );
};
