import React from 'react';

export interface AgentDownloadScreenProps {
  apiToken?: string;
  isTokenCopied?: boolean;
  showDesktopPreview?: boolean;
  isConnected?: boolean;
}

export const MockAgentDownloadScreen: React.FC<AgentDownloadScreenProps> = ({
  apiToken = 'ps_live_89f02c4819e4a3b70e',
  isTokenCopied = false,
  showDesktopPreview = true,
  isConnected = true,
}) => {
  return (
    <div
      style={{
        width: 1280,
        height: 720,
        backgroundColor: '#070b14',
        borderRadius: 24,
        border: '2px solid #1e293b',
        boxShadow: '0 25px 60px -15px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.08)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        color: '#ffffff',
        position: 'relative',
      }}
    >
      {/* Top Browser Bar */}
      <div
        style={{
          height: 44,
          backgroundColor: '#0b1120',
          borderBottom: '1px solid #1e293b',
          display: 'flex',
          alignItems: 'center',
          padding: '0 18px',
          gap: 16,
        }}
      >
        <div style={{ display: 'flex', gap: 7 }}>
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#ef4444' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#f59e0b' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#10b981' }} />
        </div>
        <div
          style={{
            flex: 1,
            maxWidth: 620,
            margin: '0 auto',
            height: 28,
            backgroundColor: '#020617',
            borderRadius: 8,
            border: '1px solid #1e293b',
            display: 'flex',
            alignItems: 'center',
            padding: '0 12px',
            fontSize: 12,
            color: '#94a3b8',
            fontFamily: "'Plus Jakarta Sans', monospace",
            gap: 8,
          }}
        >
          <span style={{ color: '#10b981' }}>🔒</span>
          <span>https://printsheba.com/shop/agent-setup</span>
        </div>
      </div>

      {/* Main Content Area */}
      <div style={{ flex: 1, padding: '24px 36px', overflowY: 'hidden', display: 'flex', flexDirection: 'column', gap: 16 }}>
        <div>
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '3px 12px', borderRadius: 9999, backgroundColor: 'rgba(244,63,94,0.15)', border: '1px solid rgba(244,63,94,0.3)', color: '#fb7185', fontSize: 11, fontWeight: 800, textTransform: 'uppercase' }}>
            <span>💻</span> Standalone Windows Executable (.EXE)
          </div>
          <h1 style={{ fontSize: 24, fontWeight: 900, margin: '6px 0 0 0' }}>
            উইন্ডোজ অটো-প্রিন্ট এজেন্ট সফটওয়্যার (Windows Print Agent)
          </h1>
          <p style={{ fontSize: 13, color: '#94a3b8', margin: '4px 0 0 0' }}>
            আপনার দোকানের কম্পিউটারে এই লাইটওয়েট সফটওয়্যারটি ব্যাকগ্রাউন্ডে চালু থাকলেই কাস্টমারের পেইড অর্ডার স্বয়ংক্রিয়ভাবে প্রিন্ট হয়ে বের হবে।
          </p>
        </div>

        {/* 2 Panels: Left Download Card & Right Live Desktop/Tray Simulation */}
        <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 1fr', gap: 20, flex: 1 }}>
          {/* Download & Token Card */}
          <div
            style={{
              padding: '24px',
              borderRadius: 20,
              backgroundImage: 'linear-gradient(135deg, #0f172a 0%, #070b14 100%)',
              border: '1.5px solid #1e293b',
              display: 'flex',
              flexDirection: 'column',
              gap: 16,
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
              <span style={{ width: 10, height: 10, borderRadius: '50%', backgroundColor: '#10b981', boxShadow: '0 0 8px #10b981' }} />
              <span style={{ fontSize: 12, fontWeight: 800, color: '#10b981', textTransform: 'uppercase' }}>
                ভার্সন v2.6 (রিয়েল-টাইম অটো-সিঙ্ক) • প্রস্তুত ও কম্পাইল্ড
              </span>
            </div>

            <div>
              <h2 style={{ fontSize: 20, fontWeight: 900, margin: 0, color: '#ffffff' }}>
                PrintShebaAgent.exe (মাত্র ৩০ KB)
              </h2>
              <p style={{ fontSize: 12, color: '#94a3b8', marginTop: 6, lineHeight: 1.5 }}>
                কোনো Node.js বা জটিল কমান্ড প্রম্পট লাগবে না। জিপ ফাইলটি ডাউনলোড করে আনজিপ করুন এবং .EXE ফাইলটি ডাবল ক্লিক করে রান করুন।
              </p>
            </div>

            {/* Badges */}
            <div style={{ display: 'flex', gap: 8, flexWrap: 'wrap' }}>
              <span style={{ padding: '4px 10px', borderRadius: 8, backgroundColor: '#1e293b', fontSize: 11, fontWeight: 700, color: '#cbd5e1' }}>✓ Windows 7 / 8 / 10 / 11</span>
              <span style={{ padding: '4px 10px', borderRadius: 8, backgroundColor: '#1e293b', fontSize: 11, fontWeight: 700, color: '#cbd5e1' }}>✓ সাইলেন্ট প্রিন্টার ইঞ্জিন</span>
              <span style={{ padding: '4px 10px', borderRadius: 8, backgroundColor: '#1e293b', fontSize: 11, fontWeight: 700, color: '#cbd5e1' }}>✓ সিস্টেম ট্রে আইকন</span>
            </div>

            {/* Download Buttons */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 8, marginTop: 'auto' }}>
              <div
                style={{
                  padding: '12px 20px',
                  borderRadius: 14,
                  backgroundImage: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                  color: '#ffffff',
                  fontSize: 13,
                  fontWeight: 900,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: 8,
                  boxShadow: '0 8px 20px rgba(16,185,129,0.3)',
                }}
              >
                <span>📦</span> সম্পূর্ণ প্যাকেজ জিপ (ZIP) ডাউনলোড
              </div>

              {/* API Token Box */}
              <div style={{ padding: '12px 14px', borderRadius: 14, backgroundColor: '#020617', border: '1px solid #1e293b', display: 'flex', flexDirection: 'column', gap: 6 }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 11, color: '#94a3b8' }}>
                  <span>দোকানের সিকিউর API টোকেন:</span>
                  <span style={{ color: isTokenCopied ? '#10b981' : '#f43f5e', fontWeight: 800, cursor: 'pointer' }}>
                    {isTokenCopied ? '✓ কপি হয়েছে!' : '📋 কপি করুন'}
                  </span>
                </div>
                <div style={{ padding: '8px 12px', borderRadius: 8, backgroundColor: '#0f172a', border: '1px solid #334155', fontFamily: 'monospace', fontSize: 12, color: '#f43f5e', fontWeight: 700 }}>
                  {apiToken}
                </div>
              </div>
            </div>
          </div>

          {/* Right: Windows Desktop Taskbar & Running Agent Console Preview */}
          <div
            style={{
              borderRadius: 20,
              backgroundColor: '#020617',
              border: '1.5px solid #1e293b',
              padding: '20px',
              display: 'flex',
              flexDirection: 'column',
              gap: 12,
              position: 'relative',
              overflow: 'hidden',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                <span style={{ fontSize: 16 }}>🖥️</span>
                <span style={{ fontSize: 13, fontWeight: 900, color: '#ffffff' }}>কম্পিউটারে লাইভ রানিং ভিউ</span>
              </div>
              <span style={{ fontSize: 11, color: isConnected ? '#10b981' : '#f59e0b', fontWeight: 800 }}>
                {isConnected ? '● Connected' : '○ Standby'}
              </span>
            </div>

            {/* CMD / Agent Console */}
            <div
              style={{
                flex: 1,
                backgroundColor: '#050914',
                borderRadius: 12,
                border: '1px solid #1e293b',
                padding: '12px 14px',
                fontFamily: "'Courier New', Courier, monospace",
                fontSize: 11,
                color: '#94a3b8',
                display: 'flex',
                flexDirection: 'column',
                gap: 6,
                lineHeight: 1.4,
              }}
            >
              <div style={{ color: '#10b981', fontWeight: 700 }}>
                [PrintSheba Desktop Agent v2.6]
              </div>
              <div>[INFO] Loading config.json... Done!</div>
              <div>[INFO] Token: ps_live_89f02c4819e... [VALID]</div>
              <div>[PRINTER] Found HP LaserJet Pro MFP M428fdw</div>
              <div>[PRINTER] Found Epson EcoTank L8050</div>
              <div style={{ color: '#38bdf8' }}>
                [POLL] Connected to cloud relay. Latency: 18ms
              </div>
              <div style={{ color: '#a7f3d0', backgroundColor: 'rgba(16,185,129,0.1)', padding: '4px 6px', borderRadius: 6 }}>
                ✓ LISTENING FOR PRINT JOBS (Ready 24/7)
              </div>
            </div>

            {/* Windows 11 Taskbar Mockup */}
            <div
              style={{
                height: 46,
                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                borderRadius: 12,
                border: '1px solid #334155',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '0 14px',
              }}
            >
              {/* Start & App Icons */}
              <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
                <span style={{ fontSize: 16 }}>🪟</span>
                <span style={{ fontSize: 14 }}>📁</span>
                <span style={{ fontSize: 14 }}>🌐</span>
              </div>

              {/* System Tray with Green Agent Pulse */}
              <div style={{ display: 'flex', gap: 10, alignItems: 'center' }}>
                {/* PrintSheba Tray Icon */}
                <div
                  style={{
                    padding: '3px 8px',
                    borderRadius: 6,
                    backgroundColor: 'rgba(16,185,129,0.2)',
                    border: '1px solid #10b981',
                    display: 'flex',
                    alignItems: 'center',
                    gap: 5,
                  }}
                >
                  <span style={{ width: 6, height: 6, borderRadius: '50%', backgroundColor: '#10b981' }} />
                  <span style={{ fontSize: 10, fontWeight: 900, color: '#34d399' }}>PrintSheba</span>
                </div>
                <span style={{ fontSize: 11, color: '#94a3b8' }}>10:15 AM</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
