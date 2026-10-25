import React from 'react';

export interface RatesSetupScreenProps {
  bwRate?: number;
  colorRate?: number;
  duplexDiscount?: number;
  bkashNumber?: string;
  nagadNumber?: string;
  hasColor?: boolean;
  hasDuplex?: boolean;
  isSaved?: boolean;
}

export const MockRatesSetupScreen: React.FC<RatesSetupScreenProps> = ({
  bwRate = 3.0,
  colorRate = 10.0,
  duplexDiscount = 1.0,
  bkashNumber = '01712-345678',
  nagadNumber = '01898-765432',
  hasColor = true,
  hasDuplex = true,
  isSaved = false,
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
          <span>https://printsheba.com/shop/rates</span>
        </div>
      </div>

      {/* Main Content Area */}
      <div style={{ flex: 1, padding: '24px 36px', overflowY: 'hidden', display: 'flex', flexDirection: 'column', gap: 18 }}>
        {/* Title Bar */}
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
          <div>
            <h1 style={{ fontSize: 24, fontWeight: 900, margin: 0, display: 'flex', alignItems: 'center', gap: 8 }}>
              <span>💰</span> প্রিন্ট রেট ও পেমেন্ট ওয়ালেট সেটিংস
            </h1>
            <p style={{ fontSize: 13, color: '#94a3b8', margin: '4px 0 0 0' }}>
              আপনার দোকানের নিজস্ব প্রিন্ট চার্জ এবং সরাসরি বিকাশ ও নগদ পার্সোনাল নম্বর নির্ধারণ করুন
            </p>
          </div>
          {isSaved && (
            <div
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: 8,
                padding: '8px 18px',
                borderRadius: 9999,
                backgroundColor: 'rgba(16,185,129,0.2)',
                border: '1.5px solid #10b981',
                color: '#34d399',
                fontSize: 13,
                fontWeight: 800,
              }}
            >
              ✓ সেটিংস সফলভাবে সংরক্ষিত হয়েছে!
            </div>
          )}
        </div>

        {/* 2 Column Settings Form */}
        <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 24, flex: 1 }}>
          {/* Left Column: Print Rates */}
          <div
            style={{
              backgroundColor: '#0f172a',
              borderRadius: 20,
              border: '1.5px solid #1e293b',
              padding: '22px 24px',
              display: 'flex',
              flexDirection: 'column',
              gap: 16,
            }}
          >
            <div style={{ fontSize: 13, fontWeight: 800, color: '#f43f5e', textTransform: 'uppercase', letterSpacing: '0.05em', borderBottom: '1px solid #1e293b', paddingBottom: 8 }}>
              📄 পেজ প্রতি প্রিন্ট চার্জ (টাকায়)
            </div>

            {/* B&W Rate Input */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#cbd5e1' }}>
                সাদাকালো (Black & White) প্রতি পেজ রেট
              </label>
              <div style={{ display: 'flex', alignItems: 'center', backgroundColor: '#020617', border: '1.5px solid #334155', borderRadius: 12, padding: '10px 14px', gap: 8 }}>
                <span style={{ fontSize: 16, fontWeight: 900, color: '#94a3b8' }}>৳</span>
                <span style={{ fontSize: 18, fontWeight: 900, color: '#ffffff', fontFamily: 'monospace', flex: 1 }}>{bwRate.toFixed(2)}</span>
                <span style={{ fontSize: 11, color: '#64748b' }}>টাকা / পেজ</span>
              </div>
            </div>

            {/* Color Rate Input */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#cbd5e1' }}>
                রঙিন (Color Print) প্রতি পেজ রেট
              </label>
              <div style={{ display: 'flex', alignItems: 'center', backgroundColor: '#020617', border: '1.5px solid #ec4899', borderRadius: 12, padding: '10px 14px', gap: 8 }}>
                <span style={{ fontSize: 16, fontWeight: 900, color: '#ec4899' }}>৳</span>
                <span style={{ fontSize: 18, fontWeight: 900, color: '#ffffff', fontFamily: 'monospace', flex: 1 }}>{colorRate.toFixed(2)}</span>
                <span style={{ fontSize: 11, color: '#ec4899', fontWeight: 700 }}>কালার প্রিন্ট</span>
              </div>
            </div>

            {/* Duplex Discount */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#cbd5e1' }}>
                এপিঠ-ওপিঠ (Duplex) ছাড় বা ছাড়মূল্য
              </label>
              <div style={{ display: 'flex', alignItems: 'center', backgroundColor: '#020617', border: '1.5px solid #334155', borderRadius: 12, padding: '10px 14px', gap: 8 }}>
                <span style={{ fontSize: 16, fontWeight: 900, color: '#10b981' }}>-৳</span>
                <span style={{ fontSize: 18, fontWeight: 900, color: '#ffffff', fontFamily: 'monospace', flex: 1 }}>{duplexDiscount.toFixed(2)}</span>
                <span style={{ fontSize: 11, color: '#10b981', fontWeight: 700 }}>ছাড় প্রতি উভয় পিঠ</span>
              </div>
            </div>

            {/* Toggles */}
            <div style={{ display: 'flex', gap: 12, marginTop: 'auto' }}>
              <div style={{ flex: 1, padding: '10px 12px', borderRadius: 12, backgroundColor: hasColor ? 'rgba(236,72,153,0.15)' : '#020617', border: hasColor ? '1px solid #ec4899' : '1px solid #334155', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <span style={{ fontSize: 12, fontWeight: 700 }}>🎨 কালার সার্ভিস</span>
                <span style={{ color: hasColor ? '#ec4899' : '#64748b', fontWeight: 900 }}>{hasColor ? '✓ অন' : '✗ অফ'}</span>
              </div>
              <div style={{ flex: 1, padding: '10px 12px', borderRadius: 12, backgroundColor: hasDuplex ? 'rgba(16,185,129,0.15)' : '#020617', border: hasDuplex ? '1px solid #10b981' : '1px solid #334155', display: 'flex', alignItems: 'center', justifyContent: 'space-between' }}>
                <span style={{ fontSize: 12, fontWeight: 700 }}>📄 বোথ সাইড</span>
                <span style={{ color: hasDuplex ? '#10b981' : '#64748b', fontWeight: 900 }}>{hasDuplex ? '✓ অন' : '✗ অফ'}</span>
              </div>
            </div>
          </div>

          {/* Right Column: Payment Wallet Numbers */}
          <div
            style={{
              backgroundColor: '#0f172a',
              borderRadius: 20,
              border: '1.5px solid #1e293b',
              padding: '22px 24px',
              display: 'flex',
              flexDirection: 'column',
              gap: 16,
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', borderBottom: '1px solid #1e293b', paddingBottom: 8 }}>
              <span style={{ fontSize: 13, fontWeight: 800, color: '#e11d48', textTransform: 'uppercase', letterSpacing: '0.05em' }}>
                📱 মোবাইল ব্যাংকিং ওয়ালেট
              </span>
              <span style={{ fontSize: 11, color: '#10b981', fontWeight: 700 }}>
                ১০০% সরাসরি আপনার নিজস্ব নম্বরে টাকা আসবে
              </span>
            </div>

            {/* bKash Number */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#f43f5e' }}>
                বিকাশ পার্সোনাল নম্বর (bKash Wallet)
              </label>
              <div style={{ display: 'flex', alignItems: 'center', backgroundColor: '#020617', border: '1.5px solid #f43f5e', borderRadius: 12, padding: '10px 14px', gap: 10 }}>
                <span style={{ padding: '3px 8px', borderRadius: 6, backgroundColor: '#f43f5e', color: '#fff', fontSize: 11, fontWeight: 900 }}>bKash</span>
                <span style={{ fontSize: 17, fontWeight: 900, color: '#ffffff', fontFamily: 'monospace', letterSpacing: '0.05em', flex: 1 }}>{bkashNumber}</span>
                <span style={{ color: '#10b981', fontSize: 12 }}>✓ সক্রিয়</span>
              </div>
            </div>

            {/* Nagad Number */}
            <div style={{ display: 'flex', flexDirection: 'column', gap: 6 }}>
              <label style={{ fontSize: 12, fontWeight: 700, color: '#f59e0b' }}>
                নগদ পার্সোনাল নম্বর (Nagad Wallet)
              </label>
              <div style={{ display: 'flex', alignItems: 'center', backgroundColor: '#020617', border: '1.5px solid #f59e0b', borderRadius: 12, padding: '10px 14px', gap: 10 }}>
                <span style={{ padding: '3px 8px', borderRadius: 6, backgroundColor: '#f59e0b', color: '#000', fontSize: 11, fontWeight: 900 }}>Nagad</span>
                <span style={{ fontSize: 17, fontWeight: 900, color: '#ffffff', fontFamily: 'monospace', letterSpacing: '0.05em', flex: 1 }}>{nagadNumber}</span>
                <span style={{ color: '#10b981', fontSize: 12 }}>✓ সক্রিয়</span>
              </div>
            </div>

            {/* Cash Note */}
            <div style={{ padding: '12px 14px', borderRadius: 12, backgroundColor: 'rgba(59,130,246,0.1)', border: '1px solid rgba(59,130,246,0.3)', display: 'flex', gap: 10, alignItems: 'center' }}>
              <span style={{ fontSize: 18 }}>💵</span>
              <div style={{ fontSize: 11, color: '#93c5fd', lineHeight: 1.4 }}>
                <strong>দোকানে সরাসরি ক্যাশ পেমেন্ট:</strong> কাস্টমার ক্যাশ সিলেক্ট করলে ড্যাশবোর্ডে "অনুমোদন" বাটনে ক্লিক করলেই সাথে সাথে প্রিন্ট হবে।
              </div>
            </div>

            {/* Save Button */}
            <div style={{ marginTop: 'auto', display: 'flex', justifyContent: 'flex-end' }}>
              <div
                style={{
                  padding: '12px 28px',
                  borderRadius: 14,
                  backgroundColor: '#10b981',
                  color: '#000000',
                  fontSize: 14,
                  fontWeight: 900,
                  boxShadow: '0 8px 20px rgba(16,185,129,0.3)',
                  display: 'flex',
                  alignItems: 'center',
                  gap: 8,
                }}
              >
                💾 সেটিংস সেভ করুন
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
