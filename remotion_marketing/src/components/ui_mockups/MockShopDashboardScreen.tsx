import React from 'react';
import { Img, staticFile } from 'remotion';

export interface ShopDashboardState {
  activeTab?: 'dashboard' | 'rates' | 'printers' | 'agent' | 'qr';
  highlightAction?: string;
  isApproving?: boolean;
}

export const MockShopDashboardScreen: React.FC<ShopDashboardState> = ({
  activeTab = 'dashboard',
  highlightAction,
  isApproving = false,
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
      }}
    >
      {/* Browser Window Chrome Top Header */}
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
        {/* Window controls */}
        <div style={{ display: 'flex', gap: 7 }}>
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#ef4444' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#f59e0b' }} />
          <div style={{ width: 12, height: 12, borderRadius: '50%', backgroundColor: '#10b981' }} />
        </div>

        {/* Address Bar */}
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
          <span>https://printsheba.com/shop/{activeTab}</span>
        </div>
      </div>

      {/* Main Dashboard Content Area */}
      <div style={{ flex: 1, padding: '24px 30px', display: 'flex', flexDirection: 'column', gap: 20, overflowY: 'hidden' }}>
        
        {/* Top Welcome Banner with Live Hardware Visualization */}
        <div
          style={{
            padding: '20px 24px',
            borderRadius: 20,
            backgroundImage: 'linear-gradient(135deg, #0f172a 0%, #090d16 100%)',
            border: '1.5px solid #1e293b',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            boxShadow: '0 10px 25px rgba(0,0,0,0.4)',
          }}
        >
          <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
            <div
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: 8,
                padding: '4px 12px',
                borderRadius: 9999,
                backgroundColor: 'rgba(16,185,129,0.15)',
                border: '1px solid rgba(16,185,129,0.3)',
                color: '#6ee7b7',
                fontSize: 11,
                fontWeight: 800,
                width: 'fit-content',
              }}
            >
              <span style={{ width: 6, height: 6, borderRadius: '50%', backgroundColor: '#10b981', boxShadow: '0 0 6px #10b981' }} />
              লাইভ অটো-প্রিন্ট ক্লাউড লিংক সক্রিয়
            </div>
            <h2 style={{ fontSize: 24, fontWeight: 900, margin: 0 }}>
              গ্রিন সাইবার ক্যাফে ও কম্পিউটার্স
            </h2>
            <div style={{ display: 'flex', gap: 10, marginTop: 4 }}>
              <span style={{ padding: '6px 14px', borderRadius: 10, backgroundColor: '#059669', color: '#fff', fontSize: 11, fontWeight: 800 }}>
                ⚡ সরাসরি ফাইল প্রিন্ট
              </span>
              <span style={{ padding: '6px 14px', borderRadius: 10, backgroundColor: '#e11d48', color: '#fff', fontSize: 11, fontWeight: 800 }}>
                🖨️ কাউন্টার QR পোস্টার
              </span>
              <span style={{ padding: '6px 14px', borderRadius: 10, backgroundColor: '#1e293b', border: '1px solid #334155', color: '#cbd5e1', fontSize: 11, fontWeight: 800 }}>
                💻 উইন্ডোজ এজেন্ট (.EXE)
              </span>
            </div>
          </div>

          {/* Hardware visualizer on right */}
          <div
            style={{
              padding: '12px 18px',
              borderRadius: 16,
              backgroundColor: '#020617',
              border: '1.5px solid #1e293b',
              textAlign: 'center',
            }}
          >
            <div style={{ fontSize: 10, color: '#10b981', fontWeight: 800, fontFamily: 'monospace' }}>
              ● RELAY ACTIVE - PORT #1
            </div>
            <div style={{ padding: '6px 14px', borderRadius: 8, backgroundColor: '#ffffff', color: '#090d16', fontSize: 11, fontWeight: 900, marginTop: 6 }}>
              📄 PrintSheba Spool
            </div>
            <div style={{ fontSize: 10, color: '#64748b', marginTop: 4, fontFamily: 'monospace' }}>
              Status: Ready for Prints
            </div>
          </div>
        </div>

        {/* 3 Metrics Cards */}
        <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: 18 }}>
          <div style={{ padding: '16px 20px', borderRadius: 16, backgroundColor: '#0f172a', border: '1px solid #1e293b' }}>
            <span style={{ fontSize: 12, color: '#94a3b8', fontWeight: 700 }}>আজকের মোট সেলস</span>
            <div style={{ fontSize: 28, fontWeight: 900, color: '#10b981', marginTop: 4 }}>৳২,৪৫০.০০</div>
            <span style={{ fontSize: 11, color: '#64748b' }}>বিকাশ, নগদ ও ক্যাশ সম্পন্ন</span>
          </div>

          <div style={{ padding: '16px 20px', borderRadius: 16, backgroundColor: '#0f172a', border: '1px solid #1e293b' }}>
            <span style={{ fontSize: 12, color: '#94a3b8', fontWeight: 700 }}>মোট প্রিন্ট সম্পন্ন</span>
            <div style={{ fontSize: 28, fontWeight: 900, color: '#ffffff', marginTop: 4 }}>৮৫টি টাস্ক</div>
            <span style={{ fontSize: 11, color: '#64748b' }}>সাদাকালো ও রঙিন ডকুমেন্টস</span>
          </div>

          <div style={{ padding: '16px 20px', borderRadius: 16, backgroundColor: '#0f172a', border: '1px solid #1e293b' }}>
            <span style={{ fontSize: 12, color: '#94a3b8', fontWeight: 700 }}>প্রিন্ট কিউতে অপেক্ষারত</span>
            <div style={{ fontSize: 28, fontWeight: 900, color: '#f59e0b', marginTop: 4 }}>২টি টাস্ক</div>
            <span style={{ fontSize: 11, color: '#f59e0b', fontWeight: 700 }}>● লাইভ অটো-প্রসেসিং হচ্ছে</span>
          </div>
        </div>

        {/* Live Print Queue Table */}
        <div
          style={{
            flex: 1,
            borderRadius: 18,
            backgroundColor: '#0f172a',
            border: '1.5px solid #1e293b',
            overflow: 'hidden',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          {/* Table Header */}
          <div
            style={{
              padding: '12px 20px',
              backgroundColor: '#090d16',
              borderBottom: '1px solid #1e293b',
              display: 'grid',
              gridTemplateColumns: '120px 140px 180px 100px 90px 120px 120px 1fr',
              fontSize: 11,
              fontWeight: 800,
              color: '#94a3b8',
              textTransform: 'uppercase',
            }}
          >
            <span>টোকেন নং</span>
            <span>কাস্টমার/ফোন</span>
            <span>ফাইল নাম</span>
            <span>পেজ ও সাইজ</span>
            <span>বিল</span>
            <span>পেমেন্ট</span>
            <span>স্ট্যাটাস</span>
            <span style={{ textAlign: 'right' }}>অ্যাকশন</span>
          </div>

          {/* Table Rows */}
          <div style={{ display: 'flex', flexDirection: 'column' }}>
            {/* Row 1: Active Printing */}
            <div
              style={{
                padding: '12px 20px',
                borderBottom: '1px solid #1e293b',
                display: 'grid',
                gridTemplateColumns: '120px 140px 180px 100px 90px 120px 120px 1fr',
                fontSize: 12,
                alignItems: 'center',
                backgroundColor: 'rgba(16,185,129,0.04)',
              }}
            >
              <span style={{ fontFamily: 'monospace', fontWeight: 900, color: '#ffffff' }}>#PS-89412</span>
              <span>01711-234567</span>
              <span style={{ color: '#cbd5e1', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>Proposal_Final.pdf</span>
              <span>৫ পেজ (A4)</span>
              <span style={{ fontWeight: 900, color: '#10b981' }}>৳৩০.০০</span>
              <span style={{ color: '#e2136e', fontWeight: 800 }}>bKash ✓</span>
              <span style={{ color: '#10b981', fontWeight: 800 }}>● প্রিন্ট সম্পন্ন</span>
              <div style={{ textAlign: 'right', display: 'flex', gap: 6, justifyContent: 'flex-end' }}>
                <span style={{ padding: '4px 8px', borderRadius: 6, backgroundColor: '#1e293b', fontSize: 10, fontWeight: 700 }}>📄 স্লিপ</span>
              </div>
            </div>

            {/* Row 2: Cash Payment Pending / Approving */}
            <div
              style={{
                padding: '12px 20px',
                borderBottom: '1px solid #1e293b',
                display: 'grid',
                gridTemplateColumns: '120px 140px 180px 100px 90px 120px 120px 1fr',
                fontSize: 12,
                alignItems: 'center',
                backgroundColor: isApproving ? 'rgba(245,158,11,0.1)' : 'transparent',
              }}
            >
              <span style={{ fontFamily: 'monospace', fontWeight: 900, color: '#ffffff' }}>#PS-89415</span>
              <span>01822-987654</span>
              <span style={{ color: '#cbd5e1', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>CV_Update.docx</span>
              <span>২ পেজ (A4)</span>
              <span style={{ fontWeight: 900, color: '#f59e0b' }}>৳৬.০০</span>
              <span style={{ color: '#f59e0b', fontWeight: 800 }}>ক্যাশ বকেয়া</span>
              <span style={{ color: '#f59e0b', fontWeight: 800 }}>কিউতে অপেক্ষারত</span>
              <div style={{ textAlign: 'right', display: 'flex', gap: 6, justifyContent: 'flex-end' }}>
                <span
                  style={{
                    padding: '4px 10px',
                    borderRadius: 6,
                    backgroundColor: isApproving ? '#10b981' : '#f59e0b',
                    color: '#000',
                    fontSize: 10,
                    fontWeight: 900,
                  }}
                >
                  ✓ ক্যাশ অনুমোদন
                </span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  );
};
