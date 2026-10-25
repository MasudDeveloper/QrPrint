import React from 'react';

export interface AndroidAppScreenProps {
  notificationAccessEnabled?: boolean;
  serverUrl?: string;
  shopToken?: string;
  isConnected?: boolean;
  simulatedNotification?: boolean;
}

export const MockAndroidAppScreen: React.FC<AndroidAppScreenProps> = ({
  notificationAccessEnabled = true,
  serverUrl = 'https://printsheba.com',
  shopToken = 'ps_live_89f02c4819e4a3b70e',
  isConnected = true,
  simulatedNotification = true,
}) => {
  return (
    <div
      style={{
        width: 380,
        height: 720,
        backgroundColor: '#070b14',
        borderRadius: 40,
        border: '10px solid #1e293b',
        boxShadow: '0 25px 60px -15px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.1)',
        overflow: 'hidden',
        display: 'flex',
        flexDirection: 'column',
        fontFamily: "'Hind Siliguri', sans-serif",
        color: '#ffffff',
        position: 'relative',
      }}
    >
      {/* Top Mobile Notch / Status Bar */}
      <div
        style={{
          height: 32,
          backgroundColor: '#020617',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          padding: '0 20px',
          fontSize: 11,
          color: '#94a3b8',
          fontWeight: 700,
        }}
      >
        <span>10:30 AM</span>
        <div style={{ display: 'flex', gap: 6, alignItems: 'center' }}>
          <span style={{ color: '#10b981' }}>📶 4G</span>
          <span>🔋 95%</span>
        </div>
      </div>

      {/* App Header (Emerald Gradient matching agent_setup.blade.php) */}
      <div
        style={{
          padding: '16px 18px',
          backgroundImage: 'linear-gradient(135deg, #065f46 0%, #022c22 100%)',
          borderBottom: '1px solid rgba(16,185,129,0.3)',
          display: 'flex',
          alignItems: 'center',
          gap: 12,
        }}
      >
        <div
          style={{
            width: 42,
            height: 42,
            borderRadius: 12,
            backgroundColor: '#10b981',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            color: '#000',
            fontSize: 22,
            fontWeight: 900,
          }}
        >
          📱
        </div>
        <div style={{ flex: 1 }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
            <span style={{ fontSize: 15, fontWeight: 900 }}>PrintSheba Payment</span>
            <span style={{ fontSize: 9, padding: '2px 6px', borderRadius: 4, backgroundColor: '#047857', color: '#6ee7b7', fontWeight: 800 }}>v2.0</span>
          </div>
          <div style={{ fontSize: 11, color: '#a7f3d0' }}>
            বিকাশ ও নগদ অটো-পেমেন্ট লিসেনার
          </div>
        </div>
        <div style={{ width: 8, height: 8, borderRadius: '50%', backgroundColor: isConnected ? '#10b981' : '#f59e0b', boxShadow: isConnected ? '0 0 8px #10b981' : 'none' }} />
      </div>

      {/* App Body Content */}
      <div style={{ flex: 1, padding: '16px', display: 'flex', flexDirection: 'column', gap: 14, overflowY: 'hidden' }}>
        
        {/* Permission Toggle Card */}
        <div
          style={{
            padding: '12px 14px',
            borderRadius: 16,
            backgroundColor: '#0f172a',
            border: notificationAccessEnabled ? '1.5px solid #10b981' : '1.5px solid #334155',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: 10 }}>
            <span style={{ fontSize: 20 }}>🔔</span>
            <div>
              <div style={{ fontSize: 12, fontWeight: 800, color: '#ffffff' }}>নোটিফিকেশন অ্যাক্সেস</div>
              <div style={{ fontSize: 10, color: notificationAccessEnabled ? '#34d399' : '#94a3b8' }}>
                {notificationAccessEnabled ? 'সক্রিয় ও প্রস্তুত' : 'অনুমতি প্রয়োজন'}
              </div>
            </div>
          </div>
          <div
            style={{
              width: 44,
              height: 24,
              borderRadius: 9999,
              backgroundColor: notificationAccessEnabled ? '#10b981' : '#334155',
              padding: 2,
              display: 'flex',
              alignItems: 'center',
              justifyContent: notificationAccessEnabled ? 'flex-end' : 'flex-start',
            }}
          >
            <div style={{ width: 20, height: 20, borderRadius: '50%', backgroundColor: '#ffffff' }} />
          </div>
        </div>

        {/* Server & Token Inputs */}
        <div
          style={{
            padding: '14px',
            borderRadius: 16,
            backgroundColor: '#0f172a',
            border: '1px solid #1e293b',
            display: 'flex',
            flexDirection: 'column',
            gap: 10,
          }}
        >
          <div>
            <label style={{ fontSize: 10, fontWeight: 700, color: '#94a3b8', display: 'block', marginBottom: 4 }}>
              সার্ভার URL
            </label>
            <div style={{ padding: '8px 10px', borderRadius: 8, backgroundColor: '#020617', border: '1px solid #334155', fontSize: 11, fontFamily: 'monospace', color: '#10b981' }}>
              {serverUrl}
            </div>
          </div>

          <div>
            <label style={{ fontSize: 10, fontWeight: 700, color: '#94a3b8', display: 'block', marginBottom: 4 }}>
              দোকানের API Token
            </label>
            <div style={{ padding: '8px 10px', borderRadius: 8, backgroundColor: '#020617', border: '1px solid #334155', fontSize: 11, fontFamily: 'monospace', color: '#f43f5e' }}>
              {shopToken.slice(0, 16)}...
            </div>
          </div>

          <div
            style={{
              padding: '8px',
              borderRadius: 10,
              backgroundColor: isConnected ? 'rgba(16,185,129,0.2)' : '#1e293b',
              color: isConnected ? '#34d399' : '#cbd5e1',
              fontSize: 11,
              fontWeight: 800,
              textAlign: 'center',
              border: isConnected ? '1px solid #10b981' : 'none',
            }}
          >
            {isConnected ? '✓ সার্ভার সংযোগ সফল ও রিয়েল-টাইম সিঙ্ক সচল' : 'সংযোগ যাচাই করুন'}
          </div>
        </div>

        {/* Live Incoming Payment Notification Simulation */}
        {simulatedNotification && (
          <div
            style={{
              padding: '12px 14px',
              borderRadius: 16,
              backgroundImage: 'linear-gradient(135deg, rgba(226,19,110,0.2) 0%, rgba(15,23,42,0.9) 100%)',
              border: '1.5px solid #e2136e',
              display: 'flex',
              flexDirection: 'column',
              gap: 8,
              boxShadow: '0 10px 20px rgba(226,19,110,0.2)',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
                <span style={{ padding: '2px 6px', borderRadius: 4, backgroundColor: '#e2136e', color: '#fff', fontSize: 9, fontWeight: 900 }}>bKash</span>
                <span style={{ fontSize: 11, fontWeight: 800, color: '#ffffff' }}>নতুন পেমেন্ট পাওয়া গেছে</span>
              </div>
              <span style={{ fontSize: 9, color: '#cbd5e1' }}>এইমাত্র</span>
            </div>

            <div style={{ fontSize: 11, color: '#f1f5f9', lineHeight: 1.4 }}>
              You have received <strong>Tk 30.00</strong> from 01711234567. Fee Tk 0.00. Balance Tk 4,520.00. TrxID <strong>9X82KLQ1</strong>
            </div>

            <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderTop: '1px solid rgba(255,255,255,0.1)', paddingTop: 6 }}>
              <span style={{ fontSize: 10, color: '#10b981', fontWeight: 800 }}>
                ⚡ অর্ডার #PS-89412 প্রিন্টারে পাঠানো হয়েছে!
              </span>
              <span style={{ fontSize: 12 }}>🖨️</span>
            </div>
          </div>
        )}

        {/* Security badge at bottom */}
        <div style={{ marginTop: 'auto', padding: '10px', borderRadius: 12, backgroundColor: 'rgba(59,130,246,0.1)', border: '1px solid rgba(59,130,246,0.2)', textAlign: 'center', fontSize: 10, color: '#93c5fd' }}>
          🛡️ কোনো ক্ষতিকর এসএমএস পারমিশন লাগে না • নিরাপদ নোটিফিকেশন ইঞ্জিন
        </div>
      </div>
    </div>
  );
};
