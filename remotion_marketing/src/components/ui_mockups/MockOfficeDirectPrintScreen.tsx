import React from 'react';

export interface OfficeDirectPrintProps {
  fileName?: string;
  fileSize?: string;
  printerName?: string;
  copies?: number;
  isPrinting?: boolean;
  statusText?: string;
}

export const MockOfficeDirectPrintScreen: React.FC<OfficeDirectPrintProps> = ({
  fileName = 'Monthly_Accounts_Oct2026.xlsx',
  fileSize = '420 KB (৩টি পেজ)',
  printerName = 'HP LaserJet Pro MFP M428fdw',
  copies = 3,
  isPrinting = false,
  statusText = 'সরাসরি প্রিন্টারে পাঠানো হয়েছে',
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
          <span>https://printsheba.com/shop/quick-print</span>
        </div>
      </div>

      {/* Main Content Area */}
      <div style={{ flex: 1, padding: '24px 36px', overflowY: 'hidden', display: 'flex', flexDirection: 'column', gap: 18 }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div>
            <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '3px 12px', borderRadius: 9999, backgroundColor: 'rgba(16,185,129,0.15)', border: '1px solid rgba(16,185,129,0.3)', color: '#6ee7b7', fontSize: 11, fontWeight: 800 }}>
              <span>⚡</span> অফিস ও দোকানের নিজস্ব সরাসরি প্রিন্ট (Office Mode)
            </div>
            <h1 style={{ fontSize: 24, fontWeight: 900, margin: '6px 0 0 0' }}>
              কুইক ফাইল প্রিন্ট (কোনো বিল বা পেমেন্টের প্রয়োজন নেই)
            </h1>
            <p style={{ fontSize: 13, color: '#94a3b8', margin: '4px 0 0 0' }}>
              দোকানের নিজস্ব হিসাব-নিকাশ, অফিস ফাইল বা ডকুমেন্টস এক ক্লিকে সরাসরি প্রিন্টারে পাঠান
            </p>
          </div>

          <div style={{ padding: '8px 16px', borderRadius: 12, backgroundColor: 'rgba(56,189,248,0.15)', border: '1px solid rgba(56,189,248,0.3)', color: '#38bdf8', fontSize: 12, fontWeight: 800 }}>
            🏢 আনলিমিটেড ফ্রি ইন্টারনাল প্রিন্ট
          </div>
        </div>

        {/* 2 Columns: Upload & Settings */}
        <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 1fr', gap: 24, flex: 1 }}>
          {/* Left Column: Drag & Drop Zone */}
          <div
            style={{
              borderRadius: 20,
              backgroundColor: '#0f172a',
              border: isPrinting ? '2px solid #10b981' : '2px dashed #334155',
              padding: '24px',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              justifyContent: 'center',
              textAlign: 'center',
              gap: 12,
              position: 'relative',
            }}
          >
            <div style={{ width: 64, height: 64, borderRadius: 20, backgroundColor: isPrinting ? 'rgba(16,185,129,0.2)' : 'rgba(59,130,246,0.15)', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: 32 }}>
              {isPrinting ? '🖨️' : '📄'}
            </div>

            <div>
              <div style={{ fontSize: 16, fontWeight: 900, color: '#ffffff' }}>
                {fileName}
              </div>
              <div style={{ fontSize: 12, color: '#94a3b8', marginTop: 4 }}>
                {fileSize}
              </div>
            </div>

            <div
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: 6,
                padding: '6px 14px',
                borderRadius: 9999,
                backgroundColor: isPrinting ? 'rgba(16,185,129,0.2)' : '#1e293b',
                color: isPrinting ? '#34d399' : '#cbd5e1',
                fontSize: 12,
                fontWeight: 800,
              }}
            >
              {isPrinting ? `✓ ${statusText}` : '✓ ফাইল সিলেক্টেড (রেডি টু প্রিন্ট)'}
            </div>

            <div style={{ fontSize: 11, color: '#64748b', marginTop: 8 }}>
              PDF, Word (.docx), Excel (.xlsx), JPG, PNG সরাসরি সাপোর্টেড
            </div>
          </div>

          {/* Right Column: Fast Print Configuration */}
          <div
            style={{
              borderRadius: 20,
              backgroundColor: '#0f172a',
              border: '1.5px solid #1e293b',
              padding: '22px 24px',
              display: 'flex',
              flexDirection: 'column',
              gap: 16,
            }}
          >
            <div style={{ fontSize: 13, fontWeight: 800, color: '#f43f5e', textTransform: 'uppercase', letterSpacing: '0.05em', borderBottom: '1px solid #1e293b', paddingBottom: 8 }}>
              ⚙️ প্রিন্ট অপশনস
            </div>

            {/* Target Printer */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#cbd5e1' }}>
                টার্গেট প্রিন্টার নির্বাচন করুন
              </label>
              <div style={{ padding: '10px 14px', borderRadius: 12, backgroundColor: '#020617', border: '1.5px solid #10b981', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                <span style={{ fontSize: 13, fontWeight: 800, color: '#ffffff' }}>{printerName}</span>
                <span style={{ fontSize: 10, color: '#10b981', fontWeight: 800 }}>● রেডি</span>
              </div>
            </div>

            {/* Copies */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#cbd5e1' }}>
                প্রিন্ট কপি সংখ্যা
              </label>
              <div style={{ display: 'flex', alignItems: 'center', gap: 12 }}>
                <div style={{ flex: 1, padding: '10px 14px', borderRadius: 12, backgroundColor: '#020617', border: '1px solid #334155', fontSize: 16, fontWeight: 900, fontFamily: 'monospace' }}>
                  {copies} কপি
                </div>
                <div style={{ padding: '10px 14px', borderRadius: 12, backgroundColor: '#1e293b', fontSize: 12, color: '#94a3b8' }}>
                  A4 পোর্ট্রেট
                </div>
              </div>
            </div>

            {/* No Billing Notice */}
            <div style={{ padding: '10px 14px', borderRadius: 12, backgroundColor: 'rgba(16,185,129,0.1)', border: '1px solid rgba(16,185,129,0.25)', fontSize: 11, color: '#a7f3d0' }}>
              ✓ কোনো পেমেন্ট গেটওয়ে নেই • বাটন চাপলেই উইন্ডোজ এজেন্টে তাৎক্ষণিক স্পুল হবে!
            </div>

            {/* Action Print Button */}
            <div style={{ marginTop: 'auto' }}>
              <div
                style={{
                  padding: '14px',
                  borderRadius: 14,
                  backgroundImage: isPrinting ? 'linear-gradient(135deg, #059669 0%, #047857 100%)' : 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
                  color: '#ffffff',
                  fontSize: 14,
                  fontWeight: 900,
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: 8,
                  boxShadow: '0 8px 20px rgba(16,185,129,0.3)',
                }}
              >
                <span>⚡</span> {isPrinting ? 'প্রিন্টারে পাঠানো হয়েছে!' : 'এক ক্লিকে প্রিন্ট করুন (ফ্রি)'}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
