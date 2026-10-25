import React from 'react';

export interface QrPosterScreenProps {
  shopName?: string;
  isPrinting?: boolean;
}

export const MockQrPosterScreen: React.FC<QrPosterScreenProps> = ({
  shopName = 'গ্রিন সাইবার ক্যাফে ও কম্পিউটার্স',
  isPrinting = false,
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
          <span>https://printsheba.com/shop/qr-poster</span>
        </div>
      </div>

      {/* Main Content Area */}
      <div style={{ flex: 1, padding: '24px 36px', overflowY: 'hidden', display: 'flex', gap: 32, alignItems: 'center' }}>
        {/* Left Information & Actions */}
        <div style={{ flex: 1, display: 'flex', flexDirection: 'column', gap: 16 }}>
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: 6, padding: '4px 12px', borderRadius: 9999, backgroundColor: 'rgba(225,29,72,0.15)', border: '1px solid rgba(225,29,72,0.3)', color: '#fb7185', fontSize: 11, fontWeight: 800, width: 'fit-content' }}>
            <span>🖨️</span> অফিসিয়াল কাউন্টার কিউআর পোস্টার
          </div>

          <h1 style={{ fontSize: 26, fontWeight: 900, margin: 0, lineHeight: 1.3 }}>
            দোকানের কাউন্টারে টানিয়ে দিন PrintSheba কিউআর পোস্টার!
          </h1>

          <p style={{ fontSize: 13, color: '#94a3b8', margin: 0, lineHeight: 1.6 }}>
            কাস্টমার দোকানে ঢুকে এই কিউআর স্ক্যান করলেই সরাসরি আপনার দোকানের ডেডিকেটেড প্রিন্ট পোর্টাল ওপেন হবে। ব্লুটুথ, পেনড্রাইভ বা হোয়াটসঅ্যাপে ফাইল শেয়ারের ঝামেলা থেকে চিরতরে মুক্তি!
          </p>

          <div style={{ display: 'flex', flexDirection: 'column', gap: 10, marginTop: 8 }}>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, fontSize: 12, color: '#cbd5e1' }}>
              <span style={{ width: 22, height: 22, borderRadius: '50%', backgroundColor: '#10b981', color: '#000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900 }}>✓</span>
              <span>স্ট্যান্ডার্ড A4 সাইজে পারফেক্ট প্রিন্ট কোয়ালিটি</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, fontSize: 12, color: '#cbd5e1' }}>
              <span style={{ width: 22, height: 22, borderRadius: '50%', backgroundColor: '#10b981', color: '#000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900 }}>✓</span>
              <span>সহজ বাংলা নির্দেশনা ও আকর্ষণীয় রঙিন ডিজাইন</span>
            </div>
            <div style={{ display: 'flex', alignItems: 'center', gap: 10, fontSize: 12, color: '#cbd5e1' }}>
              <span style={{ width: 22, height: 22, borderRadius: '50%', backgroundColor: '#10b981', color: '#000', display: 'flex', alignItems: 'center', justifyContent: 'center', fontWeight: 900 }}>✓</span>
              <span>বিকাশ, নগদ ও ক্যাশ পেমেন্ট লোগোসহ প্রস্তুত</span>
            </div>
          </div>

          {/* Buttons */}
          <div style={{ display: 'flex', gap: 14, marginTop: 12 }}>
            <div
              style={{
                padding: '12px 24px',
                borderRadius: 14,
                backgroundColor: isPrinting ? '#10b981' : '#e11d48',
                color: '#ffffff',
                fontSize: 13,
                fontWeight: 900,
                display: 'flex',
                alignItems: 'center',
                gap: 8,
                boxShadow: '0 8px 20px rgba(225,29,72,0.3)',
              }}
            >
              <span>🖨️</span> {isPrinting ? 'পোস্টার প্রিন্ট হচ্ছে...' : 'পোস্টার প্রিন্ট করুন'}
            </div>
            <div
              style={{
                padding: '12px 20px',
                borderRadius: 14,
                backgroundColor: '#1e293b',
                border: '1px solid #334155',
                color: '#cbd5e1',
                fontSize: 13,
                fontWeight: 800,
              }}
            >
              📥 PDF ডাউনলোড
            </div>
          </div>
        </div>

        {/* Right: Realistic Rendered A4 Poster Mockup */}
        <div
          style={{
            width: 320,
            height: 480,
            backgroundColor: '#ffffff',
            borderRadius: 18,
            boxShadow: '0 25px 50px -12px rgba(0,0,0,0.9), 0 0 0 1px rgba(255,255,255,0.2)',
            overflow: 'hidden',
            display: 'flex',
            flexDirection: 'column',
            color: '#0f172a',
            border: '8px solid #ffffff',
          }}
        >
          {/* Header Banner */}
          <div
            style={{
              padding: '14px 16px',
              backgroundImage: 'linear-gradient(135deg, #e11d48 0%, #be123c 100%)',
              color: '#ffffff',
              textAlign: 'center',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
              gap: 4,
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: 6 }}>
              <span style={{ fontSize: 16 }}>⚡</span>
              <span style={{ fontSize: 14, fontWeight: 900, letterSpacing: '0.02em' }}>PrintSheba.com</span>
            </div>
            <div style={{ fontSize: 12, fontWeight: 800, color: '#ffe4e6' }}>
              {shopName}
            </div>
          </div>

          {/* Poster Body */}
          <div style={{ flex: 1, padding: '16px', display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'space-between', textAlign: 'center' }}>
            <div>
              <h3 style={{ fontSize: 14, fontWeight: 900, color: '#0f172a', margin: 0 }}>
                মোবাইল দিয়ে স্ক্যান করে সরাসরি প্রিন্ট দিন
              </h3>
              <p style={{ fontSize: 10, color: '#64748b', margin: '4px 0 0 0' }}>
                কোনো অ্যাপ ছাড়া ক্যামেরায় স্ক্যান করুন
              </p>
            </div>

            {/* Simulated Big Crisp QR Code */}
            <div
              style={{
                width: 170,
                height: 170,
                backgroundColor: '#ffffff',
                border: '3px solid #0f172a',
                borderRadius: 16,
                padding: 10,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'relative',
              }}
            >
              {/* QR Pattern visual elements */}
              <div style={{ width: '100%', height: '100%', display: 'grid', gridTemplateColumns: 'repeat(5, 1fr)', gap: 4 }}>
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#ffffff', border: '2px solid #0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#ffffff', border: '2px solid #0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                {/* Center logo badge */}
                <div style={{ backgroundColor: '#e11d48', borderRadius: 6, display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#fff', fontSize: 12, fontWeight: 900 }}>
                  PS
                </div>
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#ffffff', border: '2px solid #0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#ffffff', border: '2px solid #0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
                <div style={{ backgroundColor: '#0f172a', borderRadius: 4 }} />
              </div>
            </div>

            {/* Steps mini banner */}
            <div style={{ display: 'flex', gap: 6, width: '100%', justifyContent: 'center' }}>
              <span style={{ padding: '3px 8px', borderRadius: 6, backgroundColor: '#f1f5f9', fontSize: 9, fontWeight: 800, color: '#334155' }}>১. স্ক্যান</span>
              <span style={{ padding: '3px 8px', borderRadius: 6, backgroundColor: '#f1f5f9', fontSize: 9, fontWeight: 800, color: '#334155' }}>২. ফাইল আপলোড</span>
              <span style={{ padding: '3px 8px', borderRadius: 6, backgroundColor: '#f1f5f9', fontSize: 9, fontWeight: 800, color: '#334155' }}>৩. অটো প্রিন্ট</span>
            </div>

            {/* Footer */}
            <div style={{ width: '100%', borderTop: '1px dashed #cbd5e1', paddingTop: 6, display: 'flex', justifyContent: 'space-between', alignItems: 'center', fontSize: 9, color: '#64748b' }}>
              <span>সাদাকালো ও রঙিন</span>
              <span style={{ fontWeight: 800, color: '#e11d48' }}>পেমেন্ট: বিকাশ / নগদ / ক্যাশ</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
